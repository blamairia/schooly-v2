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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

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
                Forms\Components\DatePicker::make('cassier_expiration')->nullable(),
                Forms\Components\TextInput::make('address')->required(),
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
                                        ->numeric()
                                        ->required()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            // Perform calculations here but don’t reset the value in real-time
                                            $amountPaid = $get('amount_paid') ?? 0;
                                            $amountDue = max($state - $amountPaid, 0);

                                            // Just update amount_due
                                            $set('amount_due', $amountDue);
                                        })
                                        ->debounce(500), // Add a debounce time


                                    Forms\Components\TextInput::make('amount_paid')
                                        ->label('Amount Paid')
                                        ->numeric()
                                        ->default(0)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $totalAmount = $get('total_amount') ?? 0;

                                            // Update only if paid amount is valid
                                            if ($state > 0 && $state <= $totalAmount) {
                                                $set('amount_due', $totalAmount - $state);
                                            }
                                        }),


                                // Amount Due (Hidden)
                                Forms\Components\TextInput::make('amount_due')
                                    ->numeric()
                                    ->dehydrateStateUsing(fn ($state) => $state)
                                    ->required(),


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
                    // Fetch the pre-calculated total from the payment_totals table
                    $paymentTotal = PaymentTotal::where('student_id', $record->id)
                        ->where('payment_type_id', $paymentType->id)
                        ->value('total_amount');
                    return $paymentTotal ? number_format($paymentTotal, 2) : '0.00';
                });
        })->toArray();

        // Define filters
        $filters = [

        ];

        // Merge static and dynamic columns
        return $table

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
                ],layout: FiltersLayout::AboveContent) // Add the filter for payments made today

                ->actions([

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
        ];
    }
}
