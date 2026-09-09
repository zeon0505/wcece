<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Tagihan Baru Diterbitkan</h2>
    <p>Halo,</p>
    <p>Tagihan baru dengan nomor <strong>{{ $invoice->invoice_number }}</strong> telah diterbitkan untuk Anda.</p>
    <p>Total tagihan: <strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>.</p>
    <p>Silakan login ke dashboard Anda untuk melakukan pembayaran.</p>
    <br>
    <p>Terima kasih,<br>{{ config('app.name') }}</p>
</body>
</html>
