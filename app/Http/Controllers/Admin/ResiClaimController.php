<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResiClaim;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResiClaimController extends Controller
{
    public function index(): View
    {
        $claims = ResiClaim::with(['resi', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.claims.index', compact('claims'));
    }

    public function show(ResiClaim $claim): View
    {
        $claim->load(['resi', 'user']);
        return view('admin.claims.show', compact('claim'));
    }

    public function approve(Request $request, ResiClaim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Klaim ini sudah diproses.');
        }

        // Update the resi owner
        $claim->resi->update([
            'user_id' => $claim->user_id,
            'item_name' => $claim->item_name,
            'quantity' => $claim->quantity ?? $claim->resi->quantity,
            'shipment_type' => $claim->shipment_type ?? $claim->resi->shipment_type,
        ]);

        // Update claim status
        $claim->update([
            'status' => 'approved',
        ]);

        // Reject other pending claims for this resi
        ResiClaim::where('resi_id', $claim->resi_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Klaim berhasil disetujui. Resi telah ditambahkan ke dashboard user.');
    }

    public function reject(Request $request, ResiClaim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Klaim ini sudah diproses.');
        }

        $claim->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Klaim berhasil ditolak.');
    }
}
