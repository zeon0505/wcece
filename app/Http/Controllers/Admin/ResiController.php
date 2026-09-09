<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ResiExport;
use App\Http\Controllers\Controller;
use App\Models\Resi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResiController extends Controller
{
    public function create(): View
    {
        $shipments = \App\Models\MasterShipment::latest()->get();
        return view('admin.resis.create', compact('shipments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'resi_number' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\-_]+$/', 'unique:resis,resi_number'],
            'customer_name_snapshot' => ['nullable', 'string', 'max:255'],
            'photo_item' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $ext = $request->file('photo_item')->getClientOriginalExtension();
        $filename = 'photos/' . strtoupper($validated['resi_number']) . '_unclaimed_' . time() . '.' . $ext;
        $request->file('photo_item')->storeAs('', $filename, 'public');

        $customerName = !empty($validated['customer_name_snapshot'])
            ? $validated['customer_name_snapshot']
            : 'Unclaimed';

        $resi = Resi::create([
            'resi_number' => strtoupper($validated['resi_number']),
            'customer_name_snapshot' => $customerName,
            'quantity' => 1,
            'status' => 'arrived_wh_china',
            'photo_item' => $filename,
        ]);

        $resi->statusHistories()->create([
            'from_status' => null,
            'to_status' => 'arrived_wh_china',
            'changed_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.resis.index')
            ->with('success', 'Resi unclaimed berhasil ditambahkan.');
    }

    public function index(Request $request): View
    {
        $query = Resi::with(['user', 'masterShipment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search): void {
                $q->where('resi_number', 'like', "%{$search}%")
                  ->orWhere('customer_name_snapshot', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $resis = $query->paginate(25)->withQueryString();

        return view('admin.resis.index', compact('resis'));
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new ResiExport, 'data-resi-' . now()->format('Ymd-His') . '.xlsx');
    }

    public function edit(Resi $resi): View
    {
        $shipments = \App\Models\MasterShipment::latest()->get();
        return view('admin.resis.edit', compact('resi', 'shipments'));
    }

    public function update(Request $request, Resi $resi): RedirectResponse
    {
        $validated = $request->validate([
            'resi_number' => ['required', 'string', 'max:100', 'unique:resis,resi_number,' . $resi->id],
            'customer_name_snapshot' => ['nullable', 'string', 'max:255'],
            'item_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string'],
            'master_shipment_id' => ['nullable', 'exists:master_shipments,id'],
            'weight' => ['nullable', 'numeric'],
            'rate' => ['nullable', 'numeric'],
            'cargo_tax' => ['nullable', 'numeric'],
            'fee_wh' => ['nullable', 'numeric'],
            'packing' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'paid' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $resi->status;

        $resi->update([
            'resi_number' => strtoupper($validated['resi_number']),
            'customer_name_snapshot' => $validated['customer_name_snapshot'],
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'status' => $validated['status'],
            'master_shipment_id' => $validated['master_shipment_id'],
            'weight' => $validated['weight'] ?? 0,
            'rate' => $validated['rate'] ?? 0,
            'cargo_tax' => $validated['cargo_tax'] ?? 0,
            'fee_wh' => $validated['fee_wh'] ?? 0,
            'packing' => $validated['packing'] ?? 0,
            'total' => $validated['total'] ?? 0,
            'paid' => $validated['paid'] ?? 0,
            'shipped' => $request->has('shipped'),
            'notes' => $validated['notes'],
        ]);

        if ($validated['status'] !== $oldStatus) {
            $resi->statusHistories()->create([
                'from_status' => $oldStatus,
                'to_status' => $validated['status'],
                'changed_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('admin.resis.show', $resi)
            ->with('success', 'Data resi berhasil diperbarui.');
    }

    public function show(Resi $resi): View
    {
        $resi->load(['user', 'masterShipment', 'statusHistories.changedByUser']);

        return view('admin.resis.show', compact('resi'));
    }

    public function updateStatus(Request $request, Resi $resi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Resi::$statusTransitions))],
        ]);

        $resi->transitionTo($request->status, $request->user()->id);

        return back()->with('success', 'Status resi berhasil diperbarui.');
    }

    public function uploadPhoto(Request $request, Resi $resi): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'type' => ['required', 'in:wh_china,arrived_id'],
        ]);

        $ext = $request->file('photo')->getClientOriginalExtension();
        $filename = 'photos/' . $resi->resi_number . '_' . $request->type . '_' . time() . '.' . $ext;
        $request->file('photo')->storeAs('', $filename, 'public');

        $field = $request->type === 'wh_china' ? 'photo_wh_china' : 'photo_arrived_id';
        $resi->update([$field => $filename]);

        dispatch(new \App\Jobs\SendPhotoEmailNotification($resi->id, $request->type))->afterResponse();

        return back()->with('success', 'Foto berhasil diupload dan notifikasi email sedang dikirim.');
    }

    public function receivedHistory(Request $request): View
    {
        $query = Resi::with(['user', 'masterShipment'])
            ->where(function ($q) {
                $q->where('status', 'completed')
                  ->orWhereNotNull('photo_received');
            })
            ->latest('received_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search): void {
                $q->where('resi_number', 'like', "%{$search}%")
                  ->orWhere('customer_name_snapshot', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $resis = $query->paginate(25)->withQueryString();
        $search = $request->search;

        return view('admin.resis.received', compact('resis', 'search'));
    }

    public function destroy(Resi $resi): RedirectResponse
    {
        $resi->statusHistories()->delete();
        $resi->delete();

        return redirect()->route('admin.resis.index')
            ->with('success', 'Resi berhasil dihapus.');
    }
}
