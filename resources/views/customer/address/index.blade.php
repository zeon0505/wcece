@extends('layouts.app')
@section('title', 'Alamat Gudang WH China')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.6rem; font-weight:800; color:var(--ink); margin:0;">Alamat Gudang WH China</h1>
    <p style="color:var(--ink-soft); margin-top:0.25rem; font-size:0.875rem;">Gunakan alamat di bawah ini sebagai alamat tujuan saat berbelanja di e-commerce China (Taobao, 1688, JD, dll).</p>
</div>

{{-- Peringatan Penting --}}
<div style="background:#fffbeb; border:1.5px solid #fde68a; border-radius:16px; padding:1rem 1.25rem; margin-bottom:2rem; display:flex; align-items:flex-start; gap:0.75rem;">
    <div style="font-size:1.25rem; flex-shrink:0;">⚠️</div>
    <div style="font-size:0.85rem; color:#78350f; line-height:1.5;">
        <strong>Perhatian Penting:</strong> Dilarang keras menyebarluaskan alamat gudang kepada pihak luar yang tidak terdaftar. Pastikan mengisi form resi setelah belanja max 1x24 jam.
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.5rem;">

    {{-- AIR & HANDCARRY --}}
    <div style="background:var(--white); border-radius:20px; border:2px solid #bfdbfe; padding:1.5rem; box-shadow:0 8px 25px rgba(59,130,246,0.06); display:flex; flex-direction:column; justify-space-between;">
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #eff6ff;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:40px; height:40px; background:#eff6ff; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                        ✈️
                    </div>
                    <div>
                        <h3 style="font-size:1rem; font-weight:800; color:#1e40af; margin:0;">AIR &amp; HANDCARRY</h3>
                        <span style="font-size:0.72rem; color:#3b82f6; font-weight:600;">Jalur Udara / Estimasi 5-7 Hari</span>
                    </div>
                </div>
                <span style="font-size:0.7rem; font-weight:800; background:#dbeafe; color:#1e40af; padding:0.25rem 0.6rem; border-radius:20px;">VIP</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                {{-- Name --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Nama Penerima (Name)</span>
                        <button type="button" data-copy="{{ $settings['wh_air_name'] }}" onclick="copyText(this)" style="background:none; border:none; color:#2563eb; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(37,99,235,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:800; color:var(--ink); font-size:0.95rem;">{{ $settings['wh_air_name'] }}</div>
                </div>

                {{-- Phone --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">No Telepon (Phone)</span>
                        <button type="button" data-copy="{{ $settings['wh_air_phone'] }}" onclick="copyText(this)" style="background:none; border:none; color:#2563eb; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(37,99,235,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:800; font-family:'Space Mono',monospace; color:var(--ink); font-size:0.95rem;">{{ $settings['wh_air_phone'] }}</div>
                </div>

                {{-- Address --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Alamat Lengkap (Address)</span>
                        <button type="button" data-copy="{{ $settings['wh_air_address'] }}" onclick="copyText(this)" style="background:none; border:none; color:#2563eb; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(37,99,235,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:600; color:var(--ink); font-size:0.875rem; line-height:1.45; word-break:break-word;">{{ $settings['wh_air_address'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEA CARGO --}}
    <div style="background:var(--white); border-radius:20px; border:2px solid #fbcfe8; padding:1.5rem; box-shadow:0 8px 25px rgba(236,72,153,0.06); display:flex; flex-direction:column; justify-space-between;">
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #fdf2f8;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:40px; height:40px; background:#fdf2f8; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem;">
                        🚢
                    </div>
                    <div>
                        <h3 style="font-size:1rem; font-weight:800; color:#9d174d; margin:0;">SEA CARGO</h3>
                        <span style="font-size:0.72rem; color:#ec4899; font-weight:600;">Jalur Laut / Estimasi 3-4 Minggu</span>
                    </div>
                </div>
                <span style="font-size:0.7rem; font-weight:800; background:#fce7f3; color:#9d174d; padding:0.25rem 0.6rem; border-radius:20px;">STANDARD</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                {{-- Name --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Nama Penerima (Name)</span>
                        <button type="button" data-copy="{{ $settings['wh_sea_name'] }}" onclick="copyText(this)" style="background:none; border:none; color:#db2777; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(219,39,119,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:800; color:var(--ink); font-size:0.95rem;">{{ $settings['wh_sea_name'] }}</div>
                </div>

                {{-- Phone --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">No Telepon (Phone)</span>
                        <button type="button" data-copy="{{ $settings['wh_sea_phone'] }}" onclick="copyText(this)" style="background:none; border:none; color:#db2777; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(219,39,119,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:800; font-family:'Space Mono',monospace; color:var(--ink); font-size:0.95rem;">{{ $settings['wh_sea_phone'] }}</div>
                </div>

                {{-- Address --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.75rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.2rem;">
                        <span style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Alamat Lengkap (Address)</span>
                        <button type="button" data-copy="{{ $settings['wh_sea_address'] }}" onclick="copyText(this)" style="background:none; border:none; color:#db2777; font-size:0.75rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='rgba(219,39,119,0.08)';" onmouseout="this.style.background='none';">📋 Salin</button>
                    </div>
                    <div style="font-weight:600; color:var(--ink); font-size:0.875rem; line-height:1.45; word-break:break-word;">{{ $settings['wh_sea_address'] }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function copyText(btn) {
    const textToCopy = btn.getAttribute('data-copy') || '';
    
    const triggerSuccess = () => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '✓ Tersalin!';
        btn.style.color = '#16a34a';

        // Tampilkan pop-up toast kecil di sudut kanan atas
        showCopyToastNotification();

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.color = '';
        }, 2200);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy)
            .then(triggerSuccess)
            .catch(() => fallbackCopy(textToCopy, triggerSuccess));
    } else {
        fallbackCopy(textToCopy, triggerSuccess);
    }
}

function fallbackCopy(text, onSuccess) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful && typeof onSuccess === 'function') {
            onSuccess();
        }
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    
    document.body.removeChild(textArea);
}

function showCopyToastNotification() {
    let toast = document.getElementById('copy-toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'copy-toast-notification';
        toast.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.875rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.15);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-15px);
            pointer-events: none;
        `;
        document.body.appendChild(toast);
    }
    
    toast.innerHTML = '📋 <span style="color:#4ade80;">Teks Berhasil Disalin!</span>';
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';

    if (window.toastTimeout) clearTimeout(window.toastTimeout);
    window.toastTimeout = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-15px)';
    }, 2200);
}
</script>
@endsection
