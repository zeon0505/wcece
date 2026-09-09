<?php

namespace App\Http\Controllers;

use App\Models\Resi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResiController extends Controller
{
    public function create(): View
    {
        return view('customer.resi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'resi_number' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\-_]+$/', 'unique:resis,resi_number'],
            'item_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'shipment_type' => ['required', 'string', 'in:AIR,SEA,HANDCARRY'],
            'photo_item' => ['nullable', 'image', 'max:5120'], // max 5MB
            'proof_co' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'], // max 5MB
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'resi_number.unique' => 'Nomor resi ini sudah terdaftar di sistem.',
            'resi_number.regex' => 'Nomor resi hanya boleh berisi huruf, angka, strip, dan underscore.',
            'shipment_type.in' => 'Tipe pengiriman tidak valid.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_item')) {
            $photoPath = $request->file('photo_item')->store('resis', 'public');
        }

        $proofCoPath = null;
        if ($request->hasFile('proof_co')) {
            $proofCoPath = $request->file('proof_co')->store('proof_co', 'public');
        }

        $resi = Auth::user()->resis()->create([
            'resi_number' => strtoupper($validated['resi_number']),
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'shipment_type' => $validated['shipment_type'],
            'photo_item' => $photoPath,
            'proof_co' => $proofCoPath,
            'notes' => $validated['notes'],
            'status' => 'waiting_arrival',
        ]);

        // Record initial status history
        $resi->statusHistories()->create([
            'from_status' => null,
            'to_status' => 'waiting_arrival',
            'changed_by' => Auth::id(),
        ]);

        return redirect()->route('resi.show', $resi)
            ->with('success', 'Resi berhasil didaftarkan! Kami akan memperbarui status ketika paket tiba di gudang.');
    }

    public function show(Resi $resi): View
    {
        // Ensure customer only sees their own resi
        abort_unless($resi->user_id === Auth::id(), 403);

        $resi->load(['masterShipment', 'statusHistories.changedByUser']);

        return view('customer.resi.show', compact('resi'));
    }

    public function confirmReceived(Request $request, Resi $resi): RedirectResponse
    {
        abort_unless($resi->user_id === Auth::id(), 403);

        $request->validate([
            'photo_received' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'photo_received.required' => 'Wajib mengunggah foto bukti barang telah diterima.',
        ]);

        $ext = $request->file('photo_received')->getClientOriginalExtension();
        $filename = 'photos/' . $resi->resi_number . '_received_' . time() . '.' . $ext;
        $request->file('photo_received')->storeAs('', $filename, 'public');

        $resi->update([
            'photo_received' => $filename,
            'received_at' => now(),
        ]);

        $resi->transitionTo('completed', Auth::id());

        return back()->with('success', 'Terima kasih! Konfirmasi barang diterima dan foto bukti telah berhasil terkirim ke admin.');
    }
}
