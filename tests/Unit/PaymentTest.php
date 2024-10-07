<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    // Uncomment if you decide to use an in-memory database
    // use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testPaymentCreationUpdatesTotals()
    {
        // Create a PaymentMethod instance
        $paymentMethod = new PaymentMethod();
        $paymentMethod->method_name = 'Cash';

        // Create a Payment instance
        $payment = new Payment();

        // Set payment attributes
        $payment->student_id = 1;
        $payment->payment_type_id = 1;
        $payment->study_year_id = 1;
        $payment->amount_paid = 1000;
        $payment->amount_due = 500;
        $payment->part_number = 1;
        $payment->payment_method_id = 1;

        // Set the paymentMethod relationship
        $payment->setRelation('paymentMethod', $paymentMethod);

        // Mock the DB facade for the common totals table
        DB::shouldReceive('table')
            ->with('payment_totals')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                [
                    'student_id' => $payment->student_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'study_year_id' => $payment->study_year_id,
                ],
                Mockery::on(function ($data) use ($payment) {
                    // Check that the totals are updated correctly
                    $this->assertEquals(DB::raw('total_amount + 1000'), $data['total_amount']);
                    $this->assertEquals(DB::raw('total_due + 500'), $data['total_due']);
                    $this->assertEquals(DB::raw('parts_paid + 1'), $data['parts_paid']);
                    $this->assertEquals(DB::raw('total_parts + 1'), $data['total_parts']);
                    return true;
                })
            );

        // Mock the DB facade for the method-specific totals table
        DB::shouldReceive('table')
            ->with('payment_totals_cash')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                [
                    'student_id' => $payment->student_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'study_year_id' => $payment->study_year_id,
                ],
                Mockery::on(function ($data) use ($payment) {
                    // Check that the method-specific totals are updated correctly
                    $this->assertEquals(DB::raw('total_amount + 1000'), $data['total_amount']);
                    $this->assertEquals(DB::raw('total_due + 500'), $data['total_due']);
                    $this->assertEquals(DB::raw('parts_paid + 1'), $data['parts_paid']);
                    $this->assertEquals(DB::raw('total_parts + 1'), $data['total_parts']);
                    return true;
                })
            );

        // Call the method directly
        $payment->updatePaymentTotals();
    }

    public function testPaymentUpdateAdjustsTotalsWithoutMethodChange()
    {
        // Create a PaymentMethod instance
        $paymentMethod = new PaymentMethod();
        $paymentMethod->method_name = 'Cash';

        // Original payment attributes
        $originalAttributes = [
            'student_id' => 1,
            'payment_type_id' => 1,
            'study_year_id' => 1,
            'amount_paid' => 1000,
            'amount_due' => 500,
            'part_number' => 1,
            'payment_method_id' => 1,
        ];

        // New payment attributes
        $newAttributes = [
            'amount_paid' => 1500,
            'amount_due' => 0,
            'part_number' => 1,
        ];

        // Create a Payment instance
        $payment = new Payment($originalAttributes);

        // Set the paymentMethod relationship
        $payment->setRelation('paymentMethod', $paymentMethod);

        // Simulate getting the original attributes
        $payment->syncOriginal();

        // Update attributes
        $payment->amount_paid = $newAttributes['amount_paid'];
        $payment->amount_due = $newAttributes['amount_due'];
        $payment->part_number = $newAttributes['part_number'];

        // Mock the isDirty method
        $this->assertFalse($payment->isDirty('payment_method_id'));

        // Mock the DB facade for common totals
        DB::shouldReceive('table')
            ->with('payment_totals')
            ->andReturnSelf()
            ->twice();

        DB::shouldReceive('updateOrInsert')
            ->twice()
            ->with(
                [
                    'student_id' => $payment->student_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'study_year_id' => $payment->study_year_id,
                ],
                Mockery::on(function ($data) use ($originalAttributes, $newAttributes) {
                    static $callCount = 0;
                    $callCount++;

                    if ($callCount == 1) {
                        // First call: subtract original values
                        $this->assertEquals(DB::raw('total_amount - 1000'), $data['total_amount']);
                        $this->assertEquals(DB::raw('total_due - 500'), $data['total_due']);
                        $this->assertEquals(DB::raw('parts_paid - 1'), $data['parts_paid']);
                        $this->assertEquals(DB::raw('total_parts - 1'), $data['total_parts']);
                    } else {
                        // Second call: add new values
                        $this->assertEquals(DB::raw('total_amount + 1500'), $data['total_amount']);
                        $this->assertEquals(DB::raw('total_due + 0'), $data['total_due']);
                        $this->assertEquals(DB::raw('parts_paid + 1'), $data['parts_paid']);
                        $this->assertEquals(DB::raw('total_parts + 1'), $data['total_parts']);
                    }
                    return true;
                })
            );

        // Mock the DB facade for method-specific totals
        DB::shouldReceive('table')
            ->with('payment_totals_cash')
            ->andReturnSelf()
            ->times(4);

        DB::shouldReceive('updateOrInsert')
            ->times(4)
            ->with(
                Mockery::type('array'),
                Mockery::on(function ($data) use ($originalAttributes, $newAttributes) {
                    static $callCount = 0;
                    $callCount++;

                    if ($callCount == 1) {
                        // Subtract old values
                        $this->assertEquals(DB::raw('total_amount - 1000'), $data['total_amount']);
                    } elseif ($callCount == 2) {
                        // Add new values
                        $this->assertEquals(DB::raw('total_amount + 1500'), $data['total_amount']);
                    }
                    // Additional checks can be added similarly
                    return true;
                })
            );

        // Call the method
        $payment->handlePaymentMethodChange();
        $payment->updatePaymentTotals();
    }

    public function testPaymentUpdateAdjustsTotalsWithMethodChange()
    {
        // Create old and new PaymentMethod instances
        $oldPaymentMethod = new PaymentMethod();
        $oldPaymentMethod->method_name = 'Cash';

        $newPaymentMethod = new PaymentMethod();
        $newPaymentMethod->method_name = 'Card';

        // Original payment attributes
        $originalAttributes = [
            'student_id' => 1,
            'payment_type_id' => 1,
            'study_year_id' => 1,
            'amount_paid' => 1000,
            'amount_due' => 500,
            'part_number' => 1,
            'payment_method_id' => 1,
        ];

        // New payment attributes
        $newAttributes = [
            'amount_paid' => 1500,
            'amount_due' => 0,
            'part_number' => 1,
            'payment_method_id' => 2,
        ];

        // Create a Payment instance
        $payment = new Payment($originalAttributes);

        // Set the old paymentMethod relationship
        $payment->setRelation('paymentMethod', $newPaymentMethod);

        // Simulate getting the original attributes
        $payment->syncOriginal();

        // Update attributes
        $payment->amount_paid = $newAttributes['amount_paid'];
        $payment->amount_due = $newAttributes['amount_due'];
        $payment->part_number = $newAttributes['part_number'];
        $payment->payment_method_id = $newAttributes['payment_method_id'];

        // Mock the isDirty method
        $this->assertTrue($payment->isDirty('payment_method_id'));

        // Mock the PaymentMethod::find() call
        PaymentMethod::shouldReceive('find')
            ->with($originalAttributes['payment_method_id'])
            ->andReturn($oldPaymentMethod);

        // Mock the DB facade for common totals
        DB::shouldReceive('table')
            ->with('payment_totals')
            ->andReturnSelf()
            ->twice();

        DB::shouldReceive('updateOrInsert')
            ->twice()
            ->withAnyArgs()
            ->andReturnNull();

        // Mock the DB facade for old method-specific totals
        DB::shouldReceive('table')
            ->with('payment_totals_cash')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                Mockery::type('array'),
                Mockery::on(function ($data) use ($originalAttributes) {
                    // Subtract old values
                    $this->assertEquals(DB::raw('total_amount - 1000'), $data['total_amount']);
                    return true;
                })
            );

        // Mock the DB facade for new method-specific totals
        DB::shouldReceive('table')
            ->with('payment_totals_card')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                Mockery::type('array'),
                Mockery::on(function ($data) use ($newAttributes) {
                    // Add new values
                    $this->assertEquals(DB::raw('total_amount + 1500'), $data['total_amount']);
                    return true;
                })
            );

        // Call the method
        $payment->handlePaymentMethodChange();
        $payment->updatePaymentTotals();
    }

    public function testPaymentDeletionUpdatesTotals()
    {
        // Create a PaymentMethod instance
        $paymentMethod = new PaymentMethod();
        $paymentMethod->method_name = 'Cash';

        // Create a Payment instance
        $payment = new Payment();

        // Set payment attributes
        $payment->student_id = 1;
        $payment->payment_type_id = 1;
        $payment->study_year_id = 1;
        $payment->amount_paid = 1000;
        $payment->amount_due = 500;
        $payment->part_number = 1;
        $payment->payment_method_id = 1;

        // Set the paymentMethod relationship
        $payment->setRelation('paymentMethod', $paymentMethod);

        // Mock the DB facade for the common totals table
        DB::shouldReceive('table')
            ->with('payment_totals')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                [
                    'student_id' => $payment->student_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'study_year_id' => $payment->study_year_id,
                ],
                Mockery::on(function ($data) use ($payment) {
                    // Check that the totals are decremented correctly
                    $this->assertEquals(DB::raw('total_amount - 1000'), $data['total_amount']);
                    $this->assertEquals(DB::raw('total_due - 500'), $data['total_due']);
                    $this->assertEquals(DB::raw('parts_paid - 1'), $data['parts_paid']);
                    $this->assertEquals(DB::raw('total_parts - 1'), $data['total_parts']);
                    return true;
                })
            );

        // Mock the DB facade for the method-specific totals table
        DB::shouldReceive('table')
            ->with('payment_totals_cash')
            ->andReturnSelf();

        DB::shouldReceive('updateOrInsert')
            ->once()
            ->with(
                [
                    'student_id' => $payment->student_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'study_year_id' => $payment->study_year_id,
                ],
                Mockery::on(function ($data) use ($payment) {
                    // Check that the method-specific totals are decremented correctly
                    $this->assertEquals(DB::raw('total_amount - 1000'), $data['total_amount']);
                    $this->assertEquals(DB::raw('total_due - 500'), $data['total_due']);
                    $this->assertEquals(DB::raw('parts_paid - 1'), $data['parts_paid']);
                    $this->assertEquals(DB::raw('total_parts - 1'), $data['total_parts']);
                    return true;
                })
            );

        // Call the method
        $payment->deletePaymentTotals();
    }

    public function testNegativeAmountsAreHandled()
    {
        // Create a PaymentMethod instance
        $paymentMethod = new PaymentMethod();
        $paymentMethod->method_name = 'Cash';

        // Create a Payment instance
        $payment = new Payment();

        // Set negative amounts
        $payment->student_id = 1;
        $payment->payment_type_id = 1;
        $payment->study_year_id = 1;
        $payment->amount_paid = -1000;
        $payment->amount_due = -500;
        $payment->part_number = 1;
        $payment->payment_method_id = 1;

        // Set the paymentMethod relationship
        $payment->setRelation('paymentMethod', $paymentMethod);

        // You can add assertions or exceptions to ensure negative amounts are not allowed
        // For this test, we assume that negative amounts are invalid and should throw an exception

        $this->expectException(\InvalidArgumentException::class);

        // Since the code doesn't currently throw an exception, you might need to adjust your model methods
        // to throw exceptions when invalid data is encountered

        // Call the method that should handle or prevent negative amounts
        $payment->updatePaymentTotals();
    }
}
