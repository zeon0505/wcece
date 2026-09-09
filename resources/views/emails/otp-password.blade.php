<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #0f172a, #312e81); padding: 24px 20px; text-align: center; color: #ffffff;">
            <div style="font-size: 20px; font-weight: 800; letter-spacing: -0.02em;">WH CE Cargo</div>
            <div style="font-size: 13px; color: #cbd5e1; margin-top: 4px;">Kode Verifikasi OTP (Reset Password)</div>
        </div>

        {{-- Body --}}
        <div style="padding: 24px 20px;">
            <p style="font-size: 15px; margin-top: 0; color: #334155;">Halo <strong>{{ $userEmail }}</strong>,</p>
            
            <p style="font-size: 14px; line-height: 1.5; color: #334155;">
                Kami menerima permintaan <strong>reset password</strong> untuk akun Anda. Gunakan kode OTP di bawah ini:
            </p>

            {{-- OTP Box --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 24px 0; text-align: center;">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 8px;">Kode OTP (6 digit)</div>
                <div style="font-family: monospace; font-size: 32px; font-weight: 900; color: #4f46e5; letter-spacing: 12px; line-height: 1; padding-left: 12px;">{{ $otpCode }}</div>
            </div>

            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #92400e; text-align: center; font-weight: 600;">
                ⏱️ Kode ini berlaku selama <strong>10 menit</strong> dan hanya bisa digunakan <strong>1 kali</strong>.
            </div>

            <div style="margin-top: 32px; border-top: 1px dashed #cbd5e1; padding-top: 20px;">
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; text-align: center; margin: 0;">
                    ⚠️ Jika Anda <strong>tidak</strong> meminta reset password, abaikan email ini. Akun Anda tetap aman dan tidak ada perubahan yang terjadi.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            &copy; {{ date('Y') }} WH CE Cargo. All rights reserved.
        </div>

    </div>

</body>
</html>
