<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Exports\PaymentExporter;
use App\Filament\Widgets\PaymentStatss;
use App\Filament\Widgets\WeeklyPaymentStatsWidget;
use App\Models\Payment;
use App\Models\DivisionDeadline;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use App\Filament\Admin\Resources\PaymentResource\Widgets;
use App\Filament\Widgets\TodaysPaymentStatsWidget;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    use ExposesTableToWidgets;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(function (string $query) {
                        return \App\Models\Student::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                            ->get()
                            ->mapWithKeys(function ($student) {
                                return [$student->id => "{$student->first_name} {$student->last_name}"];
                            });
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('first_name')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->required(),
                        Forms\Components\TextInput::make('birth_place')
                            ->required(),
                        Forms\Components\TextInput::make('phone_number')
                            ->required(),
                        Forms\Components\Select::make('parent_id')
                            ->relationship('parent', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                            ->required(),
                        Forms\Components\Select::make('class_assigned_id')
                            ->relationship('classAssigned', 'name')
                            ->required(),
                        Forms\Components\Select::make('study_year_id')
                            ->relationship('studyYear', 'year')
                            ->required(),
                        Forms\Components\Select::make('cassier_id')
                            ->relationship('cassier', 'number')
                            ->nullable(),
                        Forms\Components\DatePicker::make('cassier_expiration')
                            ->nullable(),
                        Forms\Components\TextInput::make('address')
                            ->required(),
                    ]),
                    Forms\Components\Select::make('study_year_id')
                                    ->relationship('studyYear', 'year') // Assuming you have a relationship set up
                                    ->default(function (callable $get) {
                                        // Retrieve the latest study year ID
                                        return \App\Models\StudyYear::latest()->first()->id ?? null;
                                    })
                                    ->required()
                                    ->label('Study Year'),



                Forms\Components\Select::make('payment_type_id')
                    ->relationship('paymentType', 'name')
                    ->required(),

                Forms\Components\Select::make('division_plan_id')
                    ->relationship('divisionPlan', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('part_number', null)),

                Forms\Components\Select::make('part_number')
                    ->options(function (callable $get) {
                        $divisionPlanId = $get('division_plan_id');
                        if ($divisionPlanId) {
                            return DivisionDeadline::where('division_plan_id', $divisionPlanId)
                                ->pluck('part_number', 'part_number');
                        }
                        return [];
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $divisionPlanId = $get('division_plan_id');
                        if ($divisionPlanId && $state) {
                            $deadline = DivisionDeadline::where('division_plan_id', $divisionPlanId)
                                ->where('part_number', $state)
                                ->first();
                            if ($deadline) {
                                $set('due_date', $deadline->due_date);
                            }
                        }
                    }),

                Forms\Components\DatePicker::make('due_date')
                    ->required()
                    ->reactive(),




                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total Amount')
                        ->numeric() // Enforce numeric input
                        ->required()

                        ->debounce(500) // React when the value changes
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $amountPaid = $get('amount_paid') ?? 0;
                            $amountDue = $state - $amountPaid;

                            // Ensure amount_due is not negative
                            $set('amount_due', max($amountDue, 0));
                        }),

                    Forms\Components\TextInput::make('amount_paid')
                        ->label('Amount Paid')
                        ->numeric() // Enforce numeric input
                        ->default(0)

                        ->debounce(500)// React when the value changes
                        ->extraInputAttributes(['min' => 0]) // Prevent negative input in the UI
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $totalAmount = $get('total_amount') ?? 0;
                            $amountDue = $totalAmount - $state;

                            // Ensure the paid amount does not exceed total amount and update amount_due
                            if ($state > $totalAmount) {
                                $set('amount_paid', $totalAmount);
                                $set('amount_due', 0); // Set amount_due to 0 when total is paid
                            } else {
                                $set('amount_due', max($amountDue, 0)); // Ensure no negative amount_due
                            }
                        }),

                    Forms\Components\TextInput::make('amount_due')
                        ->label('Amount Due')
                        ->numeric()
                        ->reactive()
                        ->required()
                        ->dehydrated(false),


                        Forms\Components\Select::make('payment_method_id')
                        ->relationship('paymentMethod', 'method_name')
                        ->required()
                        ->label('Payment Method'),


                // Automatically set payment status
                Forms\Components\Select::make('status')
                    ->options([
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                    ])
                    ->default(function (callable $get) {
                        $totalAmount = $get('total_amount') ?? 0;
                        $amountPaid = $get('amount_paid') ?? 0;

                        if ($amountPaid >= $totalAmount) {
                            return 'paid';
                        } elseif ($amountPaid > 0) {
                            return 'partial';
                        }

                        return 'unpaid';
                    })
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name') // Access student relationship
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => "{$record->student->first_name} {$record->student->last_name}")
                    ->sortable()
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHas('student', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('paymentType.name'),
                            // Inline editable columns
                        // Total Amount as a TextInputColumn
                // Total Amount as a TextInputColumn
                TextInputColumn::make('total_amount')
                    ->label('Total Amount')
                    ->rules(['required', 'numeric', 'min:0']) // Ensure total_amount is >= 0
                    ->beforeStateUpdated(function ($record, $state) {
                        // Convert values to float for accurate comparison
                        $state = floatval($state);
                        $amountPaid = floatval($record->amount_paid);

                        // Validate that total_amount cannot be less than amount_paid
                        if ($state < $amountPaid) {
                            // Notify the user about the validation error
                            Notification::make()
                                ->title('Validation Error')
                                ->danger()
                                ->body('Total amount cannot be less than the amount already paid.')
                                ->send();

                            // Prevent the update by throwing a validation exception
                            throw ValidationException::withMessages([
                                'total_amount' => 'Total amount cannot be less than the amount already paid.',
                            ]);
                        }
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Recalculate amount_due and update status after validation
                        $record->amount_due = max($state - $record->amount_paid, 0);
                        $record->status = $record->amount_paid >= $state
                            ? 'paid'
                            : ($record->amount_paid > 0 ? 'partial' : 'unpaid');
                        $record->save();
                    }),

                    // Amount Paid as a TextInputColumn
                    TextInputColumn::make('amount_paid')
                        ->label('Amount Paid')
                        ->rules(['required', 'numeric', 'min:0']) // Basic rules for validation
                        ->beforeStateUpdated(function ($record, $state) {
                            // Convert values to float for accurate comparison
                            $state = floatval($state);
                            $totalAmount = floatval($record->total_amount);

                            // Validate that amount_paid does not exceed total_amount
                            if ($state > $totalAmount) {
                                // Handle the exception: notify the user and stop the update
                                Notification::make()
                                    ->title('Validation Error')
                                    ->danger()
                                    ->body('Paid amount cannot exceed the total amount.')
                                    ->send();

                                // Stop the state update
                                throw ValidationException::withMessages([
                                    'amount_paid' => 'Paid amount cannot exceed the total amount.',
                                ]);
                            }
                        })
                        ->afterStateUpdated(function ($record, $state) {
                            // After validation, update the amount_due and status
                            $record->amount_due = max($record->total_amount - $state, 0);
                            $record->status = $state >= $record->total_amount ? 'paid' : ($state > 0 ? 'partial' : 'unpaid');
                            $record->save();
                        }),




                // Amount Due as a TextInputColumn (not editable)
                TextInputColumn::make('amount_due')
                    ->label('Amount Due')
                    ->rules(['required', 'numeric'])
                    ->disabled() // Prevent editing this field
                    ->updateStateUsing(function ($record) {
                        return max($record->total_amount - $record->amount_paid, 0); // Calculate dynamically
                    }),

                // Amount Due as a TextInputColumn (this one is not editable)


                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'unpaid',    // Unpaid in red
                        'warning' => 'partial',  // Partial in yellow
                        'success' => 'paid',     // Paid in green
                    ]),
                Tables\Columns\TextColumn::make('due_date'),


               /* Tables\Columns\BadgeColumn::make('paymentMethod.method_name')
                    ->label('Payment Method')
                    ->colors([
                        'primary' => 'cash',    // Cash in primary color
                        'success' => 'card',    // Card in green
                        'warning' => 'check',   // Check in yellow
                    ]),

                */
                SelectColumn::make('payment_method_id')
                    ->label('Payment Method')
                    ->options([
                        1 => 'Cash',   // Assuming 1 is the ID for Cash
                        2 => 'Card',   // Assuming 2 is the ID for Card
                        3 => 'Check',  // Assuming 3 is the ID for Check
                    ])
                    ->selectablePlaceholder(false) // Disables placeholder selection
                    ->rules(['required']) // Validation rule
                    ->beforeStateUpdated(function ($record, $state) {
                        // Logic before updating the state
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Logic after updating the state
                        $record->payment_method_id = $state;
                        $record->save();
                    }),
            ])
            ->filters([
                // Filter by Payment Type
                SelectFilter::make('payment_type_id')
                    ->relationship('paymentType', 'name')
                    ->label('Payment Type')
                    ->placeholder('Select a Payment Type'),

                // Filter by Payment Method (Now filtering by payment_method_id)
                SelectFilter::make('payment_method_id')
                    ->relationship('paymentMethod', 'method_name') // Use payment method relationship
                    ->label('Payment Method')
                    ->placeholder('Select a Payment Method'),

                // Filter by Payment Status
                SelectFilter::make('status')
                    ->options([
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                    ])
                    ->label('Payment Status')
                    ->placeholder('Select Payment Status'),

                // Filter by Date Range
                DateFilter::make('due_date')
                    ->label('Due Date'),

                // Today Filter (Toggle Switch)
                Filter::make('today')
                    ->label('Today\'s Payments')
                    ->toggle()
                    ->query(function ($query) {
                        return $query->whereDate('created_at', today());
                    }),

            ],layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->label('Print Receipt')
                    ->url(fn (Payment $record) => route('payments.print', ['payment' => $record->id]))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activities')->url(fn($record) => PaymentResource::getUrl('activities', ['record' => $record])),


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
                ->exporter(PaymentExporter::class),
                BulkAction::make('printReceipts')
    ->label('Print Receipts')
    ->action(function (Collection $records) {
        // Extract IDs from the collection of records
        $recordIds = $records->pluck('id')->toArray();

        // Redirect to the bulk print route with the IDs
        return redirect()->route('print.bulk.receipts', ['paymentIds' => $recordIds]);
    })
    ->requiresConfirmation(),
            ]) ;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'activities' => Pages\PaymentLogPage::route('/{record}/activities'),

        ];
    }






}
