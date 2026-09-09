<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public static function get(string $key, string $default = ''): string
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }

    public static function getAll(): array
    {
        $defaults = [
            'bank_name'           => '',
            'bank_account_number' => '',
            'bank_account_name'   => '',
            'wh_air_name'         => 'Karine',
            'wh_air_phone'        => '18851998516',
            'wh_air_address'      => '江苏省南京市浦口区北新区盘城街道永锦路41号药谷创客公寓4幢104室快递服务中心',
            'wh_sea_name'         => 'Jonathan',
            'wh_sea_phone'        => '13390783414',
            'wh_sea_address'      => '江苏省南京市浦口区 宁六路219号南京信息工程大学人才公寓',
            'tnc_content'         => "1. WH tidak bertanggung jawab atas segala risiko (seller scam, barang unofficial, barang rusak/hilang saat pengiriman, kesalahan pengiriman/input alamat jika CO sendiri, dll).\n2. WH TIDAK memberikan refund dana/barang jika terjadi seller scam. Proses dispute/refund diurus mandiri oleh pembeli.\n3. WH tidak bertanggung jawab jika seller salah kirim barang/ukuran/jumlah/warna.\n4. Jika mau return barang ke seller dikenakan fee +10k/paket, tetap wajib bayar fee WH yang berlaku, dan ongkir return ditanggung pembeli/seller (hanya untuk barang yang salah kirim).\n5. Tidak menerima pembayaran COD dengan alasan apapun.\n6. Wajib mengisi form resi jika CO sendiri. MAX ISI FORM 1x24 JAM, jika tidak diisi dan paket tidak terdata/hilang bukan tanggung jawab WH.\n7. Max payment cargo tax yaitu 7x24 jam, lebih dari itu akan dikenakan denda sebesar 5k/hari/resi.\n8. Untuk barang-barang lartas maupun sensitive cargo (battery, kosmetik, skincare, makanan, alcohol, magnet) wajib konfirmasi terlebih dahulu. Jika tidak konfirmasi maka akan dikenakan denda sebesar 500 RMB (berlaku untuk alamat Air Cargo dan Sea Cargo).",
            'pricing_info'        => "Sea Cargo: Estimasi 3-4 Minggu\nAir Cargo: Estimasi 5-7 Hari",
        ];

        $settingsInDb = DB::table('settings')->pluck('value', 'key')->all();

        return array_merge($defaults, $settingsInDb);
    }

    public function index(): View
    {
        $settings = self::getAll();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'bank_name'           => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_name'   => ['nullable', 'string', 'max:150'],
            'wh_air_name'         => ['nullable', 'string', 'max:150'],
            'wh_air_phone'        => ['nullable', 'string', 'max:100'],
            'wh_air_address'      => ['nullable', 'string', 'max:1000'],
            'wh_sea_name'         => ['nullable', 'string', 'max:150'],
            'wh_sea_phone'        => ['nullable', 'string', 'max:100'],
            'wh_sea_address'      => ['nullable', 'string', 'max:1000'],
            'tnc_content'         => ['nullable', 'string'],
            'pricing_info'        => ['nullable', 'string'],
        ]);

        $keys = [
            'bank_name', 'bank_account_number', 'bank_account_name',
            'wh_air_name', 'wh_air_phone', 'wh_air_address',
            'wh_sea_name', 'wh_sea_phone', 'wh_sea_address',
            'tnc_content', 'pricing_info'
        ];

        foreach ($keys as $key) {
            $value = $request->input($key, '');
            $existing = DB::table('settings')->where('key', $key)->first();

            if ($existing) {
                DB::table('settings')->where('key', $key)->update([
                    'value' => $value,
                    'updated_at' => now(),
                ]);
            } else {
                try {
                    // Try inserting with UUID (untuk schema lokal/baru)
                    DB::table('settings')->insert([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'key' => $key,
                        'value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Fallback: Jika ID adalah integer auto-increment (server cPanel/schema lama)
                    DB::table('settings')->insert([
                        'key' => $key,
                        'value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan (Rekening, Alamat WH, TnC, Harga) berhasil disimpan.');
    }
}
