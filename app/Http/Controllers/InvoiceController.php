<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = \Illuminate\Support\Facades\Auth::user()->invoices()
            ->with(['masterShipment', 'payment'])
            ->latest()
            ->paginate(20);

        return view('customer.invoices.index', compact('invoices'));
    }

    public function show(Request $request, Invoice $invoice): View
    {
        abort_unless($invoice->user_id === \Illuminate\Support\Facades\Auth::id(), 403);

        $invoice->load(['masterShipment', 'payment', 'user']);

        $resis = $invoice->masterShipment->resis()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get();

        // Load bank account info from settings
        $bankName    = DB::table('settings')->where('key', 'bank_name')->value('value') ?? '-';
        $bankNumber  = DB::table('settings')->where('key', 'bank_account_number')->value('value') ?? '-';
        $bankHolder  = DB::table('settings')->where('key', 'bank_account_name')->value('value') ?? '-';

        return view('customer.invoices.show', compact('invoice', 'resis', 'bankName', 'bankNumber', 'bankHolder'));
    }

    public function manualProof(Request $request, Invoice $invoice): RedirectResponse
    {
        abort_unless($invoice->user_id === \Illuminate\Support\Facades\Auth::id(), 403);
        abort_unless(in_array($invoice->status, ['unpaid', 'failed']), 403);

        $request->validate([
            'proof_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof_file.required' => 'Bukti transfer wajib diunggah.',
            'proof_file.mimes'    => 'File harus berupa JPG, PNG, atau PDF.',
            'proof_file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('proof_file')->store('proofs', 'public');

        Payment::updateOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'method'     => 'manual_transfer',
                'proof_file' => $path,
                'status'     => 'pending',
                'verified_at' => null,
            ]
        );

        $invoice->update(['status' => 'pending_verification']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Bukti pembayaran berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
    }
}
