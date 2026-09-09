<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateInvoicesJob;
use App\Models\MasterShipment;
use App\Models\Resi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(Request $request): View
    {
        $typeFilter = $request->query('type', 'all');

        $query = MasterShipment::withCount('resis')
            ->with('creator')
            ->latest();

        if (in_array($typeFilter, ['SEA', 'AIR', 'HANDCARRY'])) {
            $query->where('shipment_type', $typeFilter);
        }

        $shipments = $query->paginate(20)->appends($request->query());

        // Get counts for tabs
        $counts = collect([
            'all' => MasterShipment::count(),
            'SEA' => MasterShipment::where('shipment_type', 'SEA')->count(),
            'AIR' => MasterShipment::where('shipment_type', 'AIR')->count(),
            'HANDCARRY' => MasterShipment::where('shipment_type', 'HANDCARRY')->count(),
        ]);

        return view('admin.shipments.index', compact('shipments', 'typeFilter', 'counts'));
    }

    public function create(): View
    {
        $availableResis = Resi::with('user')
            ->whereNull('master_shipment_id')
            ->get()
            ->sortBy(function ($resi) {
                return strtolower($resi->customer_name_snapshot ?: ($resi->user?->name ?? 'zzz'));
            });

        return view('admin.shipments.create', compact('availableResis'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'resi_ids'      => ['nullable', 'array'],
            'resi_ids.*'    => ['exists:resis,id'],
            'name'          => ['nullable', 'string', 'max:100'],
            'shipment_type' => ['required', 'in:SEA,AIR,HANDCARRY'],
            'status'        => ['required', 'in:packing,in_transit'],
            'notes'         => ['nullable', 'string'],
        ]);

        /** @var \App\Models\User $admin */
        $admin = $request->user();

        $shipment = MasterShipment::create([
            'code'          => MasterShipment::generateCode(),
            'name'          => $request->name,
            'shipment_type' => $request->shipment_type,
            'status'        => $request->status,
            'notes'         => $request->notes,
            'created_by'    => $admin->id,
        ]);

        $newStatus = $request->status === 'in_transit' ? 'in_transit' : 'arrived_wh_china';

        $resiCount = 0;
        if ($request->has('resi_ids') && is_array($request->resi_ids) && count($request->resi_ids) > 0) {
            // Filter out unclaimed resi (user_id = null) — must be claimed by a customer first
            $validResiIds = Resi::whereIn('id', $request->resi_ids)
                ->whereNotNull('user_id')
                ->pluck('id')
                ->toArray();

            $resiCount = count($validResiIds);
            Resi::whereIn('id', $validResiIds)->each(function (Resi $resi) use ($shipment, $admin, $newStatus): void {
                $resi->update(['master_shipment_id' => $shipment->id]);
                if ($resi->status !== $newStatus) {
                    $resi->transitionTo($newStatus, $admin->id);
                }
            });
        }

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', "Box {$shipment->name} ({$shipment->code}) berhasil dibuat dengan {$resiCount} resi.");
    }

    public function show(MasterShipment $shipment): View
    {
        $shipment->load(['resis.user', 'creator', 'invoices.user']);

        $availableResis = Resi::with('user')
            ->whereNull('master_shipment_id')
            ->where('shipment_type', $shipment->shipment_type)
            ->orderBy('customer_name_snapshot')
            ->get();

        $activeShipments = MasterShipment::where('id', '!=', $shipment->id)
            ->whereIn('status', ['pending', 'packing'])
            ->latest()
            ->get();

        return view('admin.shipments.show', compact('shipment', 'availableResis', 'activeShipments'));
    }

    public function updateStatus(Request $request, MasterShipment $shipment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:in_transit'],
        ]);

        $shipment->update([
            'status' => $request->status,
            'departed_at' => now(),
        ]);

        /** @var \App\Models\User $admin */
        $admin = $request->user();

        $shipment->resis()->each(function (Resi $resi) use ($admin, $request): void {
            if ($resi->status !== $request->status) {
                $resi->transitionTo($request->status, $admin->id);
            }
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Status box berhasil diupdate.');
    }

    public function markArrived(Request $request, MasterShipment $shipment): RedirectResponse
    {
        $request->validate([
            'arrived_at' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $shipment->update([
            'status' => 'arrived_indonesia',
            'arrived_at' => $request->arrived_at,
        ]);

        /** @var \App\Models\User $admin */
        $admin = $request->user();

        $shipment->resis()->each(function (Resi $resi) use ($admin): void {
            $resi->transitionTo('arrived_indonesia', $admin->id);

            if (request()->hasFile('photo')) {
                $file = request()->file('photo');
                $ext = $file->getClientOriginalExtension();
                $filename = 'photos/' . $resi->resi_number . '_arrived_id_' . time() . '.' . $ext;
                $file->storeAs('', $filename, 'public');
                $resi->update(['photo_arrived_id' => $filename]);
            }
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Shipment berhasil ditandai tiba di Indonesia.');
    }

    public function generateInvoices(Request $request, MasterShipment $shipment): RedirectResponse
    {
        $request->validate([
            'rate_per_gram' => ['required', 'numeric', 'min:0'],
            'handling_fee' => ['nullable', 'numeric', 'min:0'],
            'packing_fee' => ['nullable', 'numeric', 'min:0'],
            'weights' => ['required', 'array'],
            'weights.*' => ['required', 'numeric', 'min:1'],
            'handling_fees' => ['nullable', 'array'],
            'handling_fees.*' => ['nullable', 'numeric', 'min:0'],
            'packing_fees' => ['nullable', 'array'],
            'packing_fees.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $shipment->update([
            'rate_per_gram' => $request->rate_per_gram,
            'handling_fee' => $request->handling_fee ?? 0,
            'packing_fee' => $request->packing_fee ?? 0,
        ]);

        $handlingFees = $request->input('handling_fees', []);
        $packingFees = $request->input('packing_fees', []);

        // Update final weights, handling fees, and packing fees per resi
        foreach ($request->weights as $resiId => $weight) {
            $hFee = isset($handlingFees[$resiId]) ? (float)$handlingFees[$resiId] : ($request->handling_fee ?? 0);
            $pFee = isset($packingFees[$resiId]) ? (float)$packingFees[$resiId] : ($request->packing_fee ?? 0);

            Resi::where('id', $resiId)
                ->where('master_shipment_id', $shipment->id)
                ->update([
                    'final_weight_gram' => $weight,
                    'fee_wh' => $hFee,
                    'packing' => $pFee,
                ]);
        }

        dispatch(new GenerateInvoicesJob($shipment->id));

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Berhasil memulai proses pembuatan tagihan otomatis.');
    }

    public function moveResi(Request $request, MasterShipment $shipment, Resi $resi): RedirectResponse
    {
        abort_unless($resi->master_shipment_id === $shipment->id, 403, 'Resi tidak berada di dalam box ini.');

        $request->validate([
            'target_shipment_id' => ['required', 'exists:master_shipments,id'],
        ]);

        $targetShipment = MasterShipment::findOrFail($request->target_shipment_id);

        $resi->update([
            'master_shipment_id' => $targetShipment->id
        ]);

        // Option to transition resi status to match target shipment if needed
        if ($targetShipment->status === 'arrived_wh_indo') {
            $resi->transitionTo('arrived_indonesia', \Illuminate\Support\Facades\Auth::id());
        }

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', "Resi {$resi->resi_number} berhasil dipindahkan ke box {$targetShipment->name}.");
    }

    public function addResis(Request $request, MasterShipment $shipment): RedirectResponse
    {
        $request->validate([
            'resi_ids' => ['required', 'array', 'min:1'],
            'resi_ids.*' => ['exists:resis,id'],
        ]);

        /** @var \App\Models\User $admin */
        $admin = $request->user();

        // New status matches the shipment status if departed, else default to arrived_wh_china
        $newStatus = $shipment->status === 'in_transit' ? 'in_transit' : ($shipment->status === 'arrived_indonesia' ? 'arrived_indonesia' : 'arrived_wh_china');

        Resi::whereIn('id', $request->resi_ids)
            ->whereNull('master_shipment_id')
            ->each(function (Resi $resi) use ($shipment, $admin, $newStatus): void {
                $resi->update(['master_shipment_id' => $shipment->id]);
                if ($resi->status !== $newStatus) {
                    $resi->transitionTo($newStatus, $admin->id);
                }
            });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', count($request->resi_ids) . ' resi berhasil ditambahkan ke box.');
    }

    public function uploadBoxPhoto(Request $request, MasterShipment $shipment): RedirectResponse
    {
        $request->validate([
            'photo_box' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'photo_box.required' => 'Harap pilih file foto box.',
        ]);

        $ext = $request->file('photo_box')->getClientOriginalExtension();
        $filename = 'photos/box_' . $shipment->code . '_' . time() . '.' . $ext;
        $request->file('photo_box')->storeAs('', $filename, 'public');

        $shipment->update([
            'photo_box' => $filename,
            'photo_box_uploaded_at' => now(),
        ]);

        // Send email notification to all registered users synchronously
        $allUsers = \App\Models\User::whereNotNull('email')->where('email', '!=', '')->get();
        foreach ($allUsers as $user) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)
                    ->send(new \App\Mail\BoxPhotoNotificationMail($shipment, $user));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('BoxPhoto mail failed for ' . $user->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Foto box berhasil diupload dan notifikasi email sedang dikirim ke seluruh customer.');
    }
}
