<?php

namespace App\Http\Controllers;

use App\Models\Resi;
use App\Models\ResiClaim;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $resis = collect();
        $message = null;

        if ($search) {
            // Check if this looks like a resi number (alphanumeric, no spaces)
            $isResiNumber = !str_contains($search, ' ') && strlen($search) > 6;

            if ($isResiNumber) {
                // Try exact resi number match first
                $exactResi = Resi::where('resi_number', strtoupper($search))->first();
                if ($exactResi) {
                    $resis = collect([$exactResi]);
                }
            }

            // If no exact match found, or it looks like a name, search by name
            if ($resis->isEmpty()) {
                $resis = Resi::where('customer_name_snapshot', 'like', "%{$search}%")
                    ->whereNull('user_id') // only unclaimed
                    ->orderBy('customer_name_snapshot')
                    ->get();
            }

            // Filter out already claimed by others
            $resis = $resis->filter(function ($resi) {
                if (!$resi->user_id) return true;
                return $resi->user_id === Auth::id();
            });

            if ($resis->isEmpty()) {
                $message = 'Resi tidak ditemukan. Pastikan nomor resi atau nama LINE sudah benar.';
            }
        }

        return view('customer.track.index', compact('resis', 'search', 'message'));
    }

    public function claim(Request $request, Resi $resi): RedirectResponse
    {
        abort_if($resi->user_id !== null, 403, 'Resi sudah diklaim.');
        abort_if($resi->customer_name_snapshot === 'Unclaimed', 403, 'Gunakan form klaim untuk resi unclaimed.');

        $resi->update([
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil! Resi ' . $resi->resi_number . ' telah ditambahkan ke dashboard Anda.');
    }

    public function claimForm(Resi $resi): View|RedirectResponse
    {
        abort_if($resi->user_id !== null, 403, 'Resi sudah diklaim.');
        abort_if($resi->customer_name_snapshot !== 'Unclaimed', 403, 'Resi ini bukan resi unclaimed.');

        // Redirect if already has pending claim
        if (ResiClaim::where('resi_id', $resi->id)->where('user_id', Auth::id())->where('status', 'pending')->exists()) {
            return redirect()->route('track.index', ['q' => $resi->resi_number])
                ->with('message', 'Anda sudah mengajukan klaim untuk resi ini dan sedang menunggu persetujuan.');
        }

        return view('customer.track.claim', compact('resi'));
    }

    public function claimSubmit(Request $request, Resi $resi): RedirectResponse
    {
        abort_if($resi->user_id !== null, 403, 'Resi sudah diklaim.');
        abort_if($resi->customer_name_snapshot !== 'Unclaimed', 403, 'Resi ini bukan resi unclaimed.');

        // Redirect if already has pending claim
        if (ResiClaim::where('resi_id', $resi->id)->where('user_id', Auth::id())->where('status', 'pending')->exists()) {
            return redirect()->route('track.index', ['q' => $resi->resi_number])
                ->with('message', 'Anda sudah mengajukan klaim untuk resi ini dan sedang menunggu persetujuan.');
        }

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'shipment_type' => ['required', 'in:SEA,AIR,HANDCARRY'],
            'line_name' => ['required', 'string', 'max:255'],
            'pickup_code' => ['nullable', 'string', 'max:255'],
            'proof_photo' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ]);

        $ext = $request->file('proof_photo')->getClientOriginalExtension();
        $filename = 'claims/' . $resi->resi_number . '_user_' . Auth::id() . '_' . time() . '.' . $ext;
        $request->file('proof_photo')->storeAs('', $filename, 'public');

        ResiClaim::create([
            'resi_id' => $resi->id,
            'user_id' => Auth::id(),
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'shipment_type' => $validated['shipment_type'],
            'line_name' => $validated['line_name'],
            'pickup_code' => $validated['pickup_code'] ?? null,
            'proof_photo' => $filename,
            'status' => 'pending',
        ]);

        return redirect()->route('track.index', ['q' => $resi->resi_number])
            ->with('success', 'Pengajuan klaim berhasil dikirim! Silakan tunggu admin untuk memverifikasi.');
    }
}
