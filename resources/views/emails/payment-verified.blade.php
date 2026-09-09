<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembayaran Terverifikasi</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2>Halo {{ $invoice->user->name }},</h2>
    <p>Pembayaran Anda untuk Invoice <strong>#{{ $invoice->invoice_number }}</strong> sebesar <strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong> telah berhasil diverifikasi oleh Admin.</p>
    <p>Status resi Anda kini telah diperbarui menjadi <strong>Siap Kirim</strong>.</p>
    <br>
    <p>Terima kasih telah menggunakan layanan {{ config('app.name', 'WH CE') }}.</p>
</body>
</html>
