<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentVerifiedMail;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['invoice.user', 'invoice.masterShipment'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $invoice = $payment->invoice;
        $invoice->update(['status' => 'paid']);

        // Mark resis as ready_to_ship
        $invoice->masterShipment->resis()
            ->where('user_id', $invoice->user_id)
            ->where('status', 'awaiting_payment')
            ->each(fn ($resi) => $resi->transitionTo('ready_to_ship', auth()->id()));

        Mail::to($invoice->user->email)->queue(new PaymentVerifiedMail($invoice));

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $payment->invoice->update(['status' => 'failed']);

        return back()->with('success', 'Pembayaran ditolak. Customer perlu upload ulang bukti bayar.');
    }

    public function history(Request $request): View
    {
        $query = Payment::with(['invoice.user', 'invoice.masterShipment', 'verifier'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('invoice', function ($q) use ($search): void {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('masterShipment', fn ($m) => $m->where('code', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%"));
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.history', compact('payments'));
    }

    public function historyDetail(Payment $payment): View
    {
        $payment->load(['invoice.user', 'invoice.masterShipment', 'verifier']);

        $resis = \App\Models\Resi::where('master_shipment_id', $payment->invoice->master_shipment_id)
            ->where('user_id', $payment->invoice->user_id)
            ->get();

        return view('admin.payments.history_detail', compact('payment', 'resis'));
    }
}
