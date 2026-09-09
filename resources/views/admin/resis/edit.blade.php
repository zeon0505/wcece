@extends('layouts.admin')
@section('title', 'Edit Resi')

@section('content')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 class="page-title" style="font-size: 1.5rem;">Edit Resi: {{ $resi->resi_number }}</h1>
    </div>
    <a href="{{ route('admin.resis.show', $resi) }}" class="btn btn-secondary">
        &larr; Kembali
    </a>
</div>

<div class="card" style="padding:1.5rem; max-width:800px;">
    <form action="{{ route('admin.resis.update', $resi) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom:1.25rem;">
            <label class="form-label">Nomor Resi / Tracking *</label>
            <input type="text" name="resi_number" class="form-control @error('resi_number') is-invalid @enderror" value="{{ old('resi_number', $resi->resi_number) }}" required>
            @error('resi_number')<div class="invalid-feedback" style="color:var(--danger); font-size:0.875rem;">{{ $message }}</div>@enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Customer Name (Snapshot)</label>
                <input type="text" name="customer_name_snapshot" class="form-control" value="{{ old('customer_name_snapshot', $resi->customer_name_snapshot) }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $resi->item_name) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="waiting_arrival" {{ old('status', $resi->status) === 'waiting_arrival' ? 'selected' : '' }}>Menunggu Tiba Gudang China</option>
                    <option value="arrived_wh_china" {{ old('status', $resi->status) === 'arrived_wh_china' ? 'selected' : '' }}>Tiba Gudang China</option>
                    <option value="in_transit" {{ old('status', $resi->status) === 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan ke Indonesia</option>
                    <option value="arrived_indonesia" {{ old('status', $resi->status) === 'arrived_indonesia' ? 'selected' : '' }}>Tiba di Indonesia</option>
                    <option value="awaiting_payment" {{ old('status', $resi->status) === 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="ready_to_ship" {{ old('status', $resi->status) === 'ready_to_ship' ? 'selected' : '' }}>Siap Dikirim / Selesai</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Master Shipment (Box)</label>
                <select name="master_shipment_id" class="form-control">
                    <option value="">-- Belum Masuk Box --</option>
                    @foreach($shipments as $shipment)
                        <option value="{{ $shipment->id }}" {{ old('master_shipment_id', $resi->master_shipment_id) == $shipment->id ? 'selected' : '' }}>
                            {{ $shipment->batch_number }} - {{ $shipment->shipment_type }} ({{ $shipment->status }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:600; margin-top:2rem; margin-bottom:1rem; border-bottom:1px solid var(--line); padding-bottom:0.5rem;">Data Excel (Billing & Weight)</h3>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Weight</label>
                <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $resi->weight) }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Rate</label>
                <input type="number" step="0.01" name="rate" class="form-control" value="{{ old('rate', $resi->rate) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Cargo Tax</label>
                <input type="number" step="0.01" name="cargo_tax" class="form-control" value="{{ old('cargo_tax', $resi->cargo_tax) }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Fee WH</label>
                <input type="number" step="0.01" name="fee_wh" class="form-control" value="{{ old('fee_wh', $resi->fee_wh) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Packing</label>
                <input type="number" step="0.01" name="packing" class="form-control" value="{{ old('packing', $resi->packing) }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Total</label>
                <input type="number" step="0.01" name="total" class="form-control" value="{{ old('total', $resi->total) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Paid</label>
                <input type="number" step="0.01" name="paid" class="form-control" value="{{ old('paid', $resi->paid) }}">
            </div>
            <div class="form-group" style="margin-bottom:0; display:flex; align-items:flex-end;">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="checkbox" name="shipped" value="1" {{ old('shipped', $resi->shipped) ? 'checked' : '' }} style="width:1.2rem; height:1.2rem; accent-color:var(--primary);">
                    <span style="font-weight:600;">Sudah Dikirim (Shipped)</span>
                </label>
            </div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:600; margin-top:2rem; margin-bottom:1rem; border-bottom:1px solid var(--line); padding-bottom:0.5rem;">Informasi Tambahan</h3>

        <div class="form-group" style="margin-bottom:1.25rem;">
            <label class="form-label">Quantity (Estimasi jumlah barang)</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $resi->quantity) }}" required min="1">
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
            <label class="form-label">Catatan Admin</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $resi->notes) }}</textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
