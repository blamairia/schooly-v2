<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade as PDF;

class PaymentReceiptController extends Controller
{
    public function printReceipt(Payment $payment)
    {
        return view('payments\receipt', compact('payment'));
    }
     // Fix the method to retrieve the payment IDs from the request query string
     public function printBulkReceipts()
     {
         // Fetch the payment IDs from the query string
         $paymentIds = request('paymentIds', []);

         // Fetch the payments based on the provided IDs
         $payments = Payment::with(['student', 'paymentType', 'divisionPlan', 'paymentMethod'])
                             ->whereIn('id', $paymentIds)
                             ->get();

         // Pass the payments to the view
         return view('payments\bulk', compact('payments'));
     }
}
