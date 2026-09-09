<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Paket {{ $resi->resi_number }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #0f172a, #312e81); padding: 24px 20px; text-align: center; color: #ffffff;">
            <div style="font-size: 20px; font-weight: 800; letter-spacing: -0.02em;">WH CE Cargo</div>
            <div style="font-size: 13px; color: #cbd5e1; margin-top: 4px;">Notifikasi Status Paket &amp; Foto Gudang</div>
        </div>

        {{-- Body --}}
        <div style="padding: 24px 20px;">
            <p style="font-size: 15px; margin-top: 0; color: #334155;">Halo <strong>{{ $resi->user ? $resi->user->name : 'Customer' }}</strong>,</p>
            
            @if(isset($uploadedPhotoType) && $uploadedPhotoType === 'wh_china')
                <p style="font-size: 14px; line-height: 1.5; color: #334155;">
                    Foto paket Anda saat tiba di <strong>Gudang China</strong> telah diunggah oleh tim admin.
                </p>
            @elseif(isset($uploadedPhotoType) && $uploadedPhotoType === 'arrived_id')
                <p style="font-size: 14px; line-height: 1.5; color: #334155;">
                    Foto paket Anda saat <strong>Tiba di Indonesia</strong> telah diunggah oleh tim admin.
                </p>
            @else
                <p style="font-size: 14px; line-height: 1.5; color: #334155;">
                    Status resi paket Anda <strong>{{ $resi->resi_number }}</strong> telah diperbarui.
                </p>
            @endif

            {{-- Summary Card --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin: 20px 0;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 4px;">Nomor Resi</div>
                <div style="font-family: monospace; font-size: 16px; font-weight: 800; color: #0f172a; word-break: break-all;">{{ $resi->resi_number }}</div>

                <div style="margin-top: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 4px;">Nama Barang / Qty</div>
                <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $resi->item_name ?: '—' }} ({{ $resi->quantity }} pcs)</div>

                <div style="margin-top: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 4px;">Status Terbaru</div>
                <div style="display: inline-block; background: #4f46e5; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                    {{ strtoupper(str_replace('_', ' ', $resi->status)) }}
                </div>
            </div>

            {{-- Photo Section --}}
            @php
                $hasChinaPhoto = $resi->photo_wh_china && file_exists(storage_path('app/public/' . $resi->photo_wh_china));
                $hasIndoPhoto = $resi->photo_arrived_id && file_exists(storage_path('app/public/' . $resi->photo_arrived_id));
                $hasItemPhoto = $resi->photo_item && file_exists(storage_path('app/public/' . $resi->photo_item));
            @endphp

            @if($hasChinaPhoto || $hasIndoPhoto || $hasItemPhoto)
                <div style="margin: 24px 0; border-top: 1px dashed #cbd5e1; padding-top: 20px;">
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 12px;">📷 Dokumentasi Foto Paket:</div>

                    @if($hasChinaPhoto)
                        <div style="margin-bottom: 16px; background: #fafafa; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; text-align: center;">
                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">📦 Foto Gudang China</div>
                            <img src="{{ $message->embed(storage_path('app/public/' . $resi->photo_wh_china)) }}" alt="Foto Gudang China" style="max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; max-height: 350px;">
                        </div>
                    @endif

                    @if($hasIndoPhoto)
                        <div style="margin-bottom: 16px; background: #fafafa; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; text-align: center;">
                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">🇮🇩 Foto Tiba Indonesia</div>
                            <img src="{{ $message->embed(storage_path('app/public/' . $resi->photo_arrived_id)) }}" alt="Foto Tiba Indonesia" style="max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; max-height: 350px;">
                        </div>
                    @endif

                    @if($hasItemPhoto && !$hasChinaPhoto && !$hasIndoPhoto)
                        <div style="margin-bottom: 16px; background: #fafafa; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; text-align: center;">
                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">📷 Foto Referensi User</div>
                            <img src="{{ $message->embed(storage_path('app/public/' . $resi->photo_item)) }}" alt="Foto Referensi" style="max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; max-height: 350px;">
                        </div>
                    @endif
                </div>
            @endif

            {{-- CTA Button --}}
            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ route('resi.show', $resi) }}" style="display: inline-block; background: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 700; text-decoration: none;">
                    Lihat Detail Paket di Dashboard &rarr;
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b; margin-top: 24px; line-height: 1.5; text-align: center;">
                Jika Anda memiliki pertanyaan, silakan hubungi Customer Service kami.
            </p>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            &copy; {{ date('Y') }} WH CE Cargo. All rights reserved.
        </div>

    </div>

</body>
</html>
