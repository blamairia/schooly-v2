<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\DivisionDeadline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

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
                        ->reactive() // React when the value changes
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
                        ->reactive() // React when the value changes
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
                        ->required(),


                    Forms\Components\Select::make('payment_method_id') // Change to payment_method_id
                        ->relationship('paymentMethod', 'method_name') // 'paymentMethod' is the relationship defined in the model
                        ->required()
                        ->label('Payment Method')
                        ->placeholder('Select a payment method')
                        ->searchable(),


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
                Tables\Columns\TextColumn::make('student.first_name'),
                Tables\Columns\TextColumn::make('paymentType.name'),
                Tables\Columns\TextColumn::make('total_amount'),
                Tables\Columns\TextColumn::make('amount_due'),
                Tables\Columns\TextColumn::make('amount_paid'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('due_date'),
                Tables\Columns\TextColumn::make('payment_method'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
