@extends('layouts.admin')
@section('title', 'Import Excel')

@section('content')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 class="page-title">Import Excel</h1>
        <p class="page-subtitle">Upload file Excel untuk memproses kedatangan paket massal.</p>
    </div>
    <div style="display:flex; gap:0.75rem;">
        <a href="{{ route('admin.import.template') }}" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Template
        </a>
        <a href="{{ route('admin.import.logs') }}" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Riwayat Import
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; align-items:start;">
    {{-- Upload Form --}}
    <div class="card" style="border-radius:14px;">
        <div class="card-header">
            <h3 style="font-size:1rem; font-weight:700; display:flex;align-items:center;gap:0.5rem;">
                <span style="width:4px;height:18px;background:var(--blue-deep);border-radius:2px;display:inline-block;"></span>
                Upload File Excel
            </h3>
        </div>
        <div style="padding:1.5rem;">
            <form method="POST" action="{{ route('admin.import.preview') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">File Excel (.xlsx atau .csv)</label>
                    <div id="drop-zone" style="border:2px dashed var(--line); border-radius:12px; padding:2.5rem; text-align:center; cursor:pointer; transition:all 0.2s; background:rgba(255,255,255,0.4);" onclick="document.getElementById('file-input').click();" ondragover="event.preventDefault();this.style.borderColor='var(--blue-deep)';this.style.background='rgba(191,226,245,0.2)';" ondragleave="this.style.borderColor='var(--line)';this.style.background='rgba(255,255,255,0.4)';" ondrop="event.preventDefault();document.getElementById('file-input').files=event.dataTransfer.files;updateFileName(event.dataTransfer.files[0].name);">
                        <svg width="40" height="40" fill="none" stroke="var(--ink-soft)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <p id="drop-text" style="font-weight:600; color:var(--ink);">Drag & drop file di sini</p>
                        <p style="font-size:0.8rem; color:var(--ink-soft); margin-top:0.25rem;">atau klik untuk pilih file</p>
                        <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.5rem;">Format: .xlsx, .csv — Maks. 5MB</p>
                    </div>
                    <input type="file" id="file-input" name="file" accept=".xlsx,.csv" style="display:none;" onchange="updateFileName(this.files[0]?.name);" required>
                    @error('file')
                        <p style="color:var(--red); font-size:0.8rem; margin-top:0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Hasil Import
                </button>
            </form>
        </div>
    </div>

    {{-- Panduan --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        <div class="card" style="border-radius:14px;">
            <div class="card-header">
                <h3 style="font-size:1rem; font-weight:700;">Panduan Penggunaan</h3>
            </div>
            <div style="padding:1.25rem;">
                <ol style="padding-left:1.25rem; display:flex; flex-direction:column; gap:0.75rem; font-size:0.875rem; color:var(--ink);">
                    <li><strong>Download template</strong> Excel via tombol di atas.</li>
                    <li>Isi kolom: <code style="background:rgba(0,0,0,0.05); padding:0.1rem 0.3rem; border-radius:4px;">resi_number</code>, <code style="background:rgba(0,0,0,0.05); padding:0.1rem 0.3rem; border-radius:4px;">customer_name</code>, <code style="background:rgba(0,0,0,0.05); padding:0.1rem 0.3rem; border-radius:4px;">quantity</code>, <code style="background:rgba(0,0,0,0.05); padding:0.1rem 0.3rem; border-radius:4px;">notes</code>.</li>
                    <li>Upload file, lalu cek <strong>halaman preview</strong> (hijau = match, kuning = unmatched, merah = error).</li>
                    <li>Klik <strong>Konfirmasi Import</strong> untuk memproses secara massal via antrian.</li>
                </ol>
            </div>
        </div>

        <div class="card" style="border-radius:14px; border-left:3px solid var(--blue-deep);">
            <div style="padding:1.25rem;">
                <p style="font-size:0.8rem; font-weight:700; color:var(--blue-deep); margin-bottom:0.5rem;">INFO</p>
                <p style="font-size:0.8rem; color:var(--ink);">Resi yang cocok dengan data customer (<span style="color:var(--blue-deep);">status: Menunggu Tiba</span>) akan otomatis diperbarui ke <strong>Tiba Gudang China</strong> dan email notifikasi akan dikirim ke customer.</p>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(name) {
    if (name) {
        document.getElementById('drop-text').textContent = '📄 ' + name;
        document.getElementById('drop-zone').style.borderColor = 'var(--blue-deep)';
    }
}
</script>
@endsection
