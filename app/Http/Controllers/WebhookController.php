<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtrans(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;
        
        // Verify signature
        $expectedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($expectedSignature != $signatureKey) {
            Log::error('Midtrans Webhook Invalid Signature', ['request' => $request->all()]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        
        $transactionStatus = $request->transaction_status;
        
        $invoice = Invoice::where('invoice_number', $orderId)->first();
        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }
        
        // Create or update payment record
        $payment = Payment::firstOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'method' => 'midtrans_' . ($request->payment_type ?? 'unknown'),
                'amount' => $grossAmount,
            ]
        );
        
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);
            $invoice->update(['status' => 'paid']);
            
            // Transition resis to ready_to_ship
            $invoice->masterShipment->resis()
                ->where('user_id', $invoice->user_id)
                ->where('status', 'awaiting_payment')
                ->each(fn ($resi) => $resi->transitionTo('ready_to_ship'));
                
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $payment->update(['status' => 'rejected']);
            $invoice->update(['status' => 'failed']);
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
        }
        
        return response()->json(['status' => 'success']);
    }
}
