<?php


namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Filament\Admin\Resources\StudentResource\RelationManagers;
use App\Filament\Exports\StudentExporter;
use App\Models\DivisionDeadline;
use App\Models\Payment;
use App\Models\PaymentTotal;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\StudyClass;
use Carbon\Carbon;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Students';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\DatePicker::make('birth_date')->required(),
                Forms\Components\TextInput::make('birth_place')->required(),
                Forms\Components\TextInput::make('phone_number')->required(),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'first_name') // This line initializes the relationship
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->first_name} {$record->last_name}") // Concatenate first and last names
                    ->getSearchResultsUsing(function (string $query) {
                        return \App\Models\Parents::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                            ->get()
                            ->mapWithKeys(function ($parent) {
                                return [$parent->id => "{$parent->first_name} {$parent->last_name}"];
                            });
                    })
                    ->required()
                    ->editOptionForm([ // This allows editing the selected parent
                        Forms\Components\TextInput::make('first_name')->required(),
                        Forms\Components\TextInput::make('last_name')->required(),
                        Forms\Components\DatePicker::make('birth_date')->required(),
                        Forms\Components\TextInput::make('birth_place')->required(),
                        Forms\Components\TextInput::make('phone_number')->required(),
                        Forms\Components\TextInput::make('address')->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                    ])
                    ->createOptionForm([
                        Forms\Components\TextInput::make('first_name')->required(),
                        Forms\Components\TextInput::make('last_name')->required(),
                        Forms\Components\DatePicker::make('birth_date')->required(),
                        Forms\Components\TextInput::make('birth_place')->required(),
                        Forms\Components\TextInput::make('phone_number')->required(),
                        Forms\Components\TextInput::make('address')->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                    ]),
                Forms\Components\Select::make('class_assigned_id')
                    ->relationship('classAssigned', 'name')
                    ->required(),
                Forms\Components\Select::make('study_year_id')
                    ->relationship('studyYear', 'year')
                    ->required(),
                Forms\Components\Select::make('cassier_id')
                    ->relationship('cassier', 'number')
                    ->nullable()
                    ->searchable()
                    ->preload() // Preload the options for the select dropdown
                    ->options(function () {
                        return \App\Models\Cassier::where('is_rented', false) // Only show cassiers that are not rented
                            ->orderBy('number', 'asc') // Sort by the "number" attribute
                            ->paginate(10) // Show 10 at a time (Laravel pagination)
                            ->pluck('number', 'id'); // Return the 'number' for display and 'id' for the value
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('number')
                            ->required()
                            ->label('Cassier Number'),
                        Forms\Components\Toggle::make('is_rented')
                            ->default(false)
                            ->label('Is Rented')
                            ->required(),
                    ])

                    ->getOptionLabelFromRecordUsing(fn($record) => "Cassier #{$record->number}"), // Display cassier number in the dropdown

                Forms\Components\DatePicker::make('cassier_expiration')
                    ->nullable()
                    ->default('2025-06-20'), // Set default date to 20th June 2025
                Forms\Components\TextInput::make('address')->required(),
                Forms\Components\Toggle::make('external')
                    ->label('Is External')
                    ->default(false),

                // Payments Repeater (Full Width)
                Forms\Components\Repeater::make('payments')
                    ->relationship('payments')
                    ->default(collect(PaymentType::all())->map(function ($paymentType) {
                        return [
                            'payment_type_id' => $paymentType->id, // Preselect the payment type
                            'division_plan_id' => null,
                            'part_number' => null,
                            'due_date' => null,
                            'total_amount' => null,
                            'amount_paid' => 0,
                            'amount_due' => null,
                            'payment_method_id' => 1, // Default payment method
                            'status' => 'unpaid', // Default status
                        ];
                    })->toArray())
                    ->schema([
                        Forms\Components\Grid::make(5) // Adjust the grid layout as needed
                            ->schema([

                                // Payment Type
                                Forms\Components\Select::make('payment_type_id')
                                    ->relationship('paymentType', 'name')
                                    ->required()
                                    ->label('Payment Type')
                                    ->columnSpan(1),

                                // Division Plan
                                Forms\Components\Select::make('division_plan_id')
                                    ->relationship('divisionPlan', 'name')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('part_number', null))
                                    ->columnSpan(1),

                                // Part Number
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
                                    })
                                    ->columnSpan(1),

                                // Due Date (Hidden)
                                Forms\Components\DatePicker::make('due_date')
                                    ->required(),

                                // Total Amount

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


                                // Payment Method
                                Forms\Components\Select::make('payment_method_id')
                                    ->relationship('paymentMethod', 'method_name')
                                    ->required(),

                                // Status (Hidden)
                                Forms\Components\Hidden::make('status')
                                    ->default('unpaid'), // Default status to unpaid


                                Forms\Components\Select::make('study_year_id')
                                    ->relationship('studyYear', 'year') // Assuming you have a relationship set up
                                    ->default(function (callable $get) {
                                        // Retrieve the latest study year ID
                                        return \App\Models\StudyYear::latest()->first()->id ?? null;
                                    })
                                    ->required()
                                    ->label('Study Year'),

                            ])
                    ])
                    ->columns(1) // Repeater should take full width
                    ->label('Payments')
                    ->createItemButtonLabel('Add Payment')
                    ->collapsible()
                    ->minItems(1)
                    ->columnSpan('full'),// The repeater occupies full width

            ]);
    }

    public static function table(Table $table): Table
    {
        // Fetch all available payment types
        $paymentTypes = PaymentType::all();

        // Static columns for the student details
        $staticColumns = [
            TextColumn::make('first_name')->label('First Name'),
            TextColumn::make('last_name')->label('Last Name'),
            TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn (Student $record) => "{$record->first_name} {$record->last_name}")
                    ->sortable()
                    ->searchable(query: function ($query, string $search) {
                        return $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    }),
            TextColumn::make('classAssigned.name')->label('Class Assigned'),
            TextColumn::make('cassier.number')->label('Cassier'),
        ];

        // Dynamically create columns for each payment type
        $dynamicColumns = $paymentTypes->map(function ($paymentType) {
            return TextColumn::make("payment_type_{$paymentType->id}_total")
                ->label($paymentType->name)
                ->getStateUsing(function ($record) use ($paymentType) {
                    // Check if the student is external
                    if ($record->external) {
                        // If it's the 'Ecolage' payment type, show "N/A" for external students
                        if ($paymentType->name === 'Ecolage') {
                            return 'N/A';
                        }
                    }

                    // Use the eager-loaded paymentTotals relationship instead of querying
                    $paymentTotal = $record->paymentTotals
                        ->where('payment_type_id', $paymentType->id)
                        ->first();

                    return $paymentTotal ? number_format($paymentTotal->total_amount, 2) : '0.00';
                })
                ->formatStateUsing(fn($state) => $state === 'N/A' ? 'N/A' : $state)
                ->icon(fn($state) => $state === 'N/A' ? 'heroicon-o-x-circle' : null)
                ->iconPosition('after');
        })->toArray();


        // Define filters
        $filters = [

        ];

        // Merge static and dynamic columns
        return $table
            // Eager load relationships to prevent N+1 queries
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with(['paymentTotals', 'classAssigned', 'cassier', 'studyYear'])
            )
            ->columns(array_merge($staticColumns, $dynamicColumns))
            ->filters([
                Filter::make('payments_today')
                    ->label('Payments Today')
                    ->query(function (Builder $query) {
                        // Filter logic: payments made today
                        $studentIdsWithPaymentsToday = Payment::whereDate('updated_at', Carbon::today())
                            ->pluck('student_id')
                            ->unique();

                        // Apply the filter by student IDs
                        $query->whereIn('id', $studentIdsWithPaymentsToday);
                    }),

                SelectFilter::make('classAssigned')
                    ->relationship('classAssigned', 'name')
                    ->label('Class')
                    ->placeholder('All Classes')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('study_year_id')
                    ->relationship('studyYear', 'year')
                    ->label('Study Year')
                    ->placeholder('All Years')
                    ->preload(),

                Filter::make('external')
                    ->label('External Only')
                    ->toggle()
                    ->query(fn (Builder $query) => $query->where('external', true)),

                Filter::make('has_debt')
                    ->label('With Outstanding Debt')
                    ->toggle()
                    ->query(fn (Builder $query) => $query->whereHas('payments', 
                        fn ($q) => $q->where('amount_due', '>', 0)
                    )),
            ], layout: FiltersLayout::AboveContentCollapsible) // Add the filter for payments made today

                ->actions([

                Tables\Actions\Action::make('printReceipts')
                    ->label('Print Receipts')
                    ->url(function (Student $record): string {
                        // Gather payment IDs for the selected student
                        $paymentIds = Payment::where('student_id', $record->id)->pluck('id')->toArray();

                        // Return the URL to the bulk print route with payment IDs
                        return route('print.bulk.receipts', ['paymentIds' => $paymentIds]);
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->disabled(fn (Student $record): bool => Payment::where('student_id', $record->id)->doesntExist()),

                Tables\Actions\Action::make('activities')->url(fn($record) => StudentResource::getUrl('activities', ['record' => $record])),

                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
                    ->exporter(StudentExporter::class)
            ]);
    }





    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'activities' => Pages\StudentLogPage::route('/{record}/activities'),
        ];
    }
}
