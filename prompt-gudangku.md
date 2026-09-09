# Prompt: Sistem Warehouse Tracking "Gudangku" (Laravel)

Gunakan dokumen ini sebagai prompt/spesifikasi teknis untuk membangun sistem tracking paket forwarding China → Indonesia. Salin seluruh isi ini ke Claude Code / asisten dev kamu sebagai instruksi awal proyek.

---

## 1. Konteks & Tujuan

Bangun aplikasi web berbasis **Laravel** untuk jasa forwarding paket dari China ke Indonesia, dengan dua portal:

- **User Portal** — customer input nomor resi dan memantau status paketnya.
- **Admin Portal** — tim gudang mengelola kedatangan barang, konsolidasi kiriman, penimbangan, tagihan, dan verifikasi pembayaran.

Stack yang dipakai:
- Laravel 10/11 (PHP 8.2+)
- MySQL, **gunakan engine InnoDB secara eksplisit** di migration (`$table->engine = 'InnoDB';`), jangan andalkan default server.
- Auth: boleh scaffold pakai `laravel/breeze` (`--stack blade`) untuk mempercepat setup route/controller login-register-reset password bawaannya, **atau** bikin auth manual dari nol. Kalau pakai Breeze, ini WAJIB: seluruh view hasil publish Breeze (`resources/views/auth/*`, `layouts/*` bawaan) **dihapus total dan diganti custom** mengikuti design system di Section 12 — jangan sisakan tampilan bawaannya sama sekali, cuma logic-nya yang dipakai. Tambahkan kolom `role` (`customer`/`admin`) ke migration `users` bawaan Breeze.
- Laravel Queue + Mail untuk notifikasi email (gunakan queue agar proses import Excel massal tidak blocking).
- `maatwebsite/excel` (PhpSpreadsheet wrapper) untuk import/export Excel.
- Vite untuk asset build (build lokal sebelum deploy, commit hasil build — sesuai environment hosting yang tidak punya Node.js).
- Payment gateway: Midtrans atau Xendit (rekomendasi Midtrans Snap untuk kemudahan integrasi Indonesia).

---

## 2. Struktur Database

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar unique | dipakai untuk login & notifikasi |
| phone | varchar nullable | |
| password | varchar | |
| role | enum('customer','admin') | default `customer` |
| email_verified_at | timestamp nullable | |
| timestamps | | |

### `resis` (paket per customer)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | FK users, nullable | nullable karena bisa "unmatched" saat import |
| master_shipment_id | FK master_shipments, nullable | |
| resi_number | varchar unique | nomor resi dari seller China |
| item_name | varchar nullable | nama barang/produk |
| customer_name_snapshot | varchar nullable | nama dari file excel, buat pencocokan/audit |
| quantity | integer default 1 | |
| status | enum('waiting_arrival','arrived_wh_china','in_transit','arrived_indonesia','awaiting_payment','ready_to_ship') | default `waiting_arrival` |
| photo_wh_china | varchar nullable | path foto saat diterima gudang China |
| photo_arrived_id | varchar nullable | path foto saat sampai Indonesia |
| final_weight_kg | decimal(8,2) nullable | diisi admin saat sampai Indonesia |
| notes | text nullable | |
| timestamps | | |

### `master_shipments` (gabungan beberapa resi jadi satu pengiriman)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| code | varchar unique | contoh: `MS-114` |
| status | enum('in_transit','arrived_indonesia','completed') | |
| rate_per_kg | decimal(10,2) nullable | rate yang berlaku saat itu, diinput admin |
| handling_fee | decimal(10,2) default 0 | |
| departed_at | timestamp nullable | tanggal berangkat dari China |
| arrived_at | timestamp nullable | tanggal sampai Indonesia |
| created_by | FK users (admin) | |
| timestamps | | |

### `invoices`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| invoice_number | varchar unique | contoh: `INV-MS114-002` |
| user_id | FK users | |
| master_shipment_id | FK master_shipments | |
| total_weight_kg | decimal(8,2) | akumulasi berat resi milik user di shipment ini |
| rate_per_kg | decimal(10,2) | |
| handling_fee | decimal(10,2) | |
| total_amount | decimal(12,2) | dihitung: (total_weight_kg × rate_per_kg) + handling_fee |
| status | enum('unpaid','pending_verification','paid','failed') | default `unpaid` |
| due_date | date nullable | |
| timestamps | | |

### `payments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| invoice_id | FK invoices | |
| method | enum('gateway','manual_transfer') | |
| gateway_reference | varchar nullable | transaction ID dari Midtrans/Xendit |
| proof_file | varchar nullable | untuk manual transfer |
| status | enum('pending','verified','rejected') | |
| verified_by | FK users nullable | admin yang verifikasi manual |
| verified_at | timestamp nullable | |
| timestamps | | |

### `import_logs` (riwayat import Excel)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| uploaded_by | FK users (admin) | |
| file_name | varchar | |
| total_rows | integer | |
| matched_count | integer | |
| unmatched_count | integer | |
| error_count | integer | |
| raw_result | json | detail per baris untuk ditampilkan ulang di riwayat |
| timestamps | | |

### `status_histories` (audit trail perubahan status, penting untuk transparansi ke customer)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| resi_id | FK resis | |
| from_status | varchar nullable | |
| to_status | varchar | |
| changed_by | FK users nullable | null jika sistem otomatis |
| timestamps | | |

> Semua tabel pakai `$table->engine = 'InnoDB';` di migration, dan foreign key wajib pakai `foreignId()->constrained()` agar relasinya benar.

---

## 3. Relasi Model (Eloquent)

- `User` hasMany `Resi`, hasMany `Invoice`
- `Resi` belongsTo `User`, belongsTo `MasterShipment`, hasMany `StatusHistory`
- `MasterShipment` hasMany `Resi`, hasMany `Invoice`
- `Invoice` belongsTo `User`, belongsTo `MasterShipment`, hasOne `Payment`
- `Payment` belongsTo `Invoice`

---

## 4. Alur Status Resi (State Machine)

```
waiting_arrival
      │  (admin: input manual / import excel "Arrived WH China")
      ▼
arrived_wh_china
      │  (admin: gabungkan ke master shipment)
      ▼
in_transit
      │  (admin: update master shipment "arrived_indonesia" + upload foto)
      ▼
arrived_indonesia
      │  (admin: input final_weight_kg pada tiap resi → sistem generate invoice)
      ▼
awaiting_payment
      │  (customer bayar via gateway ATAU upload bukti manual → admin/gateway verifikasi)
      ▼
ready_to_ship
```

Aturan penting:
- Perubahan status **selalu** ditulis ke `status_histories` (dipakai untuk timeline di dashboard customer).
- Perubahan status pada `master_shipment` men-trigger perubahan status massal ke semua `resi` yang tergabung di dalamnya (kecuali status `awaiting_payment`/`ready_to_ship` yang levelnya per-invoice/per-user, bukan per-shipment, karena satu shipment bisa berisi banyak customer berbeda).
- Setiap resi pindah status → dispatch job `SendStatusEmailNotification` (queued).

---

## 5. Import Excel (Fitur Utama yang Diminta)

### Alur:
1. Admin download template (`resi_number`, `customer_name`, `quantity`, `notes`) via endpoint export template.
2. Admin upload file `.xlsx`/`.csv` di halaman **Import Kedatangan**.
3. Backend proses baris per baris:
   - Cocokkan `resi_number` ke tabel `resis` yang statusnya `waiting_arrival` dan `user_id` belum null.
     - **Match** → update `status = arrived_wh_china`, isi `item_name`, `quantity`, `customer_name_snapshot`; catat di `status_histories`; queue email notifikasi.
     - **Unmatched** (resi tidak ditemukan di sistem) → buat baris `resis` baru dengan `user_id = null`, status tetap `waiting_arrival` tapi ditandai perlu link manual, ATAU simpan di tabel sementara `import_logs.raw_result` supaya admin bisa link manual belakangan (rekomendasi: jangan langsung buat resi baru tanpa user, cukup simpan sebagai unmatched row di log, biar tidak ada data "yatim" di tabel resi).
   - **Error** (kolom kosong, resi duplikat dalam file yang sama, format nomor tidak valid) → tidak diproses, dicatat di `raw_result` dengan alasan errornya.
4. Sebelum commit ke database: tampilkan **halaman preview** hasil parsing (tabel dengan badge hijau/kuning/merah per baris) dan tombol "Konfirmasi Import".
5. Setelah dikonfirmasi → proses berjalan dalam **queued job** (`ProcessResiImport`) agar tidak timeout kalau filenya besar (ratusan baris), admin dapat notifikasi di dashboard saat selesai.
6. Simpan ringkasan ke `import_logs` untuk riwayat & audit ("siapa import apa, kapan, hasilnya berapa").

### Validasi wajib per baris:
- `resi_number` tidak boleh kosong & format wajar (alfanumerik).
- `quantity` harus angka > 0.
- Deteksi duplikat resi dalam satu file yang sama sebelum diproses.

### Teknis:
- Gunakan `maatwebsite/excel` dengan `ToCollection` atau `WithChunkReading` untuk file besar.
- Buat `ResiImport` class implements `ToCollection, WithHeadingRow, WithValidation`.
- Simpan file asli yang diupload ke storage untuk keperluan audit (opsional tapi disarankan).

---

## 6. Fitur — User Portal

| Fitur | Detail |
|---|---|
| Register/Login | Email + password, role otomatis `customer` |
| Input resi | Form: nomor resi (unik), nama barang (opsional), catatan. Status awal `waiting_arrival` |
| Dashboard tracking | List semua resi milik user, dikelompokkan per status (tab/filter: Waiting, Arrived WH China, In Transit, Ready to Ship) |
| Detail resi | Timeline status (dari `status_histories`), foto (jika ada), info master shipment jika sudah digabung |
| Tagihan | List invoice milik user, status bayar, detail perhitungan (berat, rate, handling fee) |
| Pembayaran | Redirect ke Midtrans Snap, atau upload bukti transfer manual |
| Notifikasi email | Terkirim otomatis tiap resi berubah status, dan saat invoice terbit |

## 7. Fitur — Admin Portal

| Fitur | Detail |
|---|---|
| Login admin | Role `admin`, guard/middleware terpisah dari customer |
| List resi masuk | Semua resi dengan filter status, pencarian by nomor resi/nama customer |
| Import Excel | Sesuai spesifikasi bagian 5 |
| Upload foto per resi | Manual per resi untuk kasus yang tidak lewat import |
| Buat master shipment | Pilih beberapa resi berstatus `arrived_wh_china` → gabung jadi 1 `master_shipment`, set `departed_at` |
| Update shipment sampai Indonesia | Upload foto bukti, set `arrived_at`, status shipment → `arrived_indonesia`, semua resi ikut update `arrived_indonesia` |
| Input berat & rate | Per resi: `final_weight_kg`. Per shipment: `rate_per_kg`, `handling_fee`. Sistem generate invoice otomatis per user (kalau 1 user punya beberapa resi dalam 1 shipment, digabung jadi 1 invoice) |
| Verifikasi pembayaran | List `payments` status `pending`, admin approve/reject manual transfer; pembayaran gateway auto-verified via webhook |
| Riwayat import | List `import_logs` dengan detail per file |

---

## 8. Payment Gateway

- Gunakan **Midtrans Snap** (rekomendasi untuk market Indonesia, support banyak metode: VA, e-wallet, QRIS).
- Endpoint `POST /invoices/{invoice}/pay` → generate Snap token, redirect customer ke halaman pembayaran.
- Webhook `POST /webhooks/midtrans` → terima notifikasi status transaksi, update `payments.status` dan `invoices.status`, jika `paid` → trigger update status resi ke `ready_to_ship` + email notifikasi.
- Simpan **signature key verification** dari Midtrans di webhook handler untuk keamanan (jangan percaya payload mentah tanpa verifikasi).
- Sediakan juga jalur **manual transfer**: customer upload bukti, admin verifikasi manual di admin portal (untuk backup kalau gateway bermasalah atau customer prefer transfer manual).

---

## 9. Notifikasi Email

Buat Mailable terpisah untuk tiap event, dikirim via queue (`ShouldQueue`):
- `ResiStatusUpdatedMail` — tiap resi pindah status
- `InvoiceGeneratedMail` — saat tagihan terbit
- `PaymentVerifiedMail` — saat pembayaran dikonfirmasi

Gunakan Laravel Notification (`Notification::send`) agar mudah diperluas ke channel lain (misal WhatsApp) di masa depan tanpa ubah banyak kode.

---

## 10. Routes Ringkas

```
// Customer
GET  /dashboard
POST /resi                     — input resi baru
GET  /resi/{resi}               — detail + timeline
GET  /invoices
GET  /invoices/{invoice}
POST /invoices/{invoice}/pay    — mulai pembayaran gateway
POST /invoices/{invoice}/manual-proof — upload bukti manual

// Admin
GET  /admin/resis
GET  /admin/import              — halaman upload excel
POST /admin/import/preview      — parsing + preview tanpa commit
POST /admin/import/confirm      — commit hasil import (queued)
POST /admin/resis/{resi}/photo
POST /admin/shipments           — buat master shipment dari resi terpilih
POST /admin/shipments/{shipment}/arrived
POST /admin/shipments/{shipment}/generate-invoices
GET  /admin/payments/pending
POST /admin/payments/{payment}/verify
POST /admin/payments/{payment}/reject

// Webhook
POST /webhooks/midtrans
```

---

## 11. Catatan Deployment (khusus environment hosting shared cPanel)

- Build asset Vite **secara lokal**, commit folder `public/build`, karena server tidak punya Node.js.
- Composer dependencies pakai `~/composer.phar` dengan `-d allow_url_fopen=On` bila diperlukan.
- Jalankan migration dengan PHP CLI eksplisit versi 8.4 (`ea-php84 artisan migrate`) kalau default CLI masih 8.2.
- Pastikan `index.php` path absolut disesuaikan ulang tiap deploy (sesuai kebiasaan deploy sebelumnya).
- Queue worker: karena shared hosting biasanya tidak bisa jalankan `queue:work` sebagai daemon, pertimbangkan pakai **cron job tiap menit** yang menjalankan `queue:work --stop-when-empty` sebagai gantinya, atau gunakan driver queue `database` + scheduler.
- Simpan file upload (foto, bukti bayar) di `storage/app/public` dan pastikan `storage:link` dijalankan ulang tiap deploy karena symlink kadang hilang di shared hosting.

---

## 12. UI & Design System (Custom, Tanpa Template Bawaan Laravel)

Kalau auth di-scaffold pakai Breeze (lihat Section 1), **hapus total** tampilan bawaannya (`resources/views/auth`, `dashboard.blade.php`, layout bawaan, dsb) — yang dipakai cuma logic route/controller-nya. Semua view dibangun custom dari nol mengikuti design system di bawah, konsisten dengan landing page yang sudah dibuat sebelumnya.

### Stack frontend
- **Blade** sebagai templating utama (server-rendered), bukan SPA — cocok untuk shared hosting karena tidak butuh Node.js runtime di server.
- **Tailwind CSS** untuk styling, dikonfigurasi custom (bukan default palette Tailwind) sesuai token warna di bawah.
- **Alpine.js** untuk interaktivitas ringan di sisi client: toggle dropdown, modal konfirmasi, preview hasil import Excel sebelum submit, tab filter status di dashboard, tanpa perlu build SPA penuh.
- Asset di-build lokal via Vite (`npm run build`) lalu **commit hasil build** ke repo, karena server hosting tidak punya Node.js — sesuai alur deploy yang sudah biasa dipakai.

### Design tokens (samakan dengan landing page)
```css
--white:      #ffffff;
--surface:    #f8fafc;
--surface-2:  #f2f6fa;
--ink:        #15181d;   /* teks utama, selalu hitam pekat ini, bukan #000 murni */
--ink-soft:   #4b5563;   /* teks sekunder/deskripsi */
--blue:       #bfe2f5;   /* aksen pastel — status "progres/tracking" */
--blue-deep:  #5fa8d3;
--pink:       #f6d3e2;   /* aksen pastel — status "perlu perhatian/tagihan" */
--pink-deep:  #e08fb2;
--red:        #e0483e;   /* HANYA untuk alert/status gagal/menunggu bayar, jangan dipakai dekoratif */
--red-soft:   #fbe1df;
--line:       #e6eaef;   /* border/divider */
```

Font: **Space Grotesk** untuk heading/display, **Inter** untuk body teks, **Space Mono** khusus untuk nomor resi/invoice (biar kerasa seperti label pengiriman asli, bukan sekadar dekorasi monospace).

### Aturan konsistensi visual
- Status resi selalu direpresentasikan dengan badge warna yang sama di semua halaman: `waiting_arrival` = abu netral, `arrived_wh_china` = biru pastel, `in_transit` = biru lebih pekat, `arrived_indonesia`/`awaiting_payment` = pink pastel, `ready_to_ship` = hitam solid (final state), gagal/reject = merah.
- Timeline status di detail resi customer pakai pola stepper yang sama seperti di kartu tracking landing page (dot + garis putus-putus penghubung).
- Tabel resi/invoice di admin portal jangan pakai card bergaya SaaS generik (rounded card + shadow abu-abu di semua sisi) — pertahankan gaya "label pengiriman/manifest": border solid, divider putus-putus, sudut tidak terlalu bulat.
- Buat Blade components reusable untuk elemen yang berulang, contoh:
  - `<x-status-badge :status="$resi->status" />`
  - `<x-tracking-stepper :history="$resi->statusHistories" />`
  - `<x-invoice-card :invoice="$invoice" />`
  - `<x-import-preview-row :row="$row" />`
  
  Ini penting supaya kalau desain badge/stepper mau diubah nanti, cukup ubah satu file component, bukan cari-cari di banyak view.
- Layout terpisah untuk customer (`layouts/app.blade.php`) dan admin (`layouts/admin.blade.php`) — nav dan struktur beda konteks, tapi tetap satu design token yang sama supaya brand-nya konsisten.
- Responsive wajib sampai mobile, karena customer kemungkinan besar cek status paket dari HP.

---

## 13. Urutan Pengerjaan yang Disarankan

1. Setup auth + role (customer/admin) + migration dasar (`users`, `resis`)
2. Fitur input resi + dashboard tracking customer (tanpa foto/shipment dulu)
3. Admin: list resi + update status manual
4. Master shipment: buat, gabungkan resi, update status
5. Import Excel (paling kompleks, kerjakan setelah alur dasar solid)
6. Invoice generation dari berat + rate
7. Payment gateway integration + webhook
8. Notifikasi email di tiap titik
9. Polish: audit trail (`status_histories`), riwayat import, halaman preview import

---

## 14. Performance & Scaling Checklist

Target awal: ±300 user aktif di shared hosting cPanel. Fokus utamanya bukan jumlah user, tapi **concurrent request** dan **proses berat di background** — dua hal ini yang paling sering bikin shared hosting "down" (sebenarnya bukan mati, tapi kehabisan slot proses PHP-FPM/CloudLinux yang biasanya dibatasi 20-40 proses bersamaan).

### 14.1 Proses berat wajib lewat queue
- Import Excel, kirim email, generate invoice **tidak boleh diproses sinkron** saat request masuk. Semua wajib `dispatch()` ke queue (`ProcessResiImport`, `SendStatusEmailNotification`, `GenerateInvoiceJob`, dst).
- Karena shared hosting tidak bisa jalankan `queue:work` sebagai daemon, buat **cron job tiap menit** yang menjalankan:
  ```
  * * * * * php /home/user/app/artisan queue:work --stop-when-empty --max-time=55
  ```
- Gunakan queue driver `database` (bukan `sync`) di `.env` production.

### 14.2 Optimasi query database
- Tambahkan index eksplisit di migration untuk kolom yang sering dicari/difilter: `resi_number`, `status`, `user_id`, `master_shipment_id`, dan semua foreign key.
  ```php
  $table->index('status');
  $table->index('resi_number');
  ```
- **Selalu paginate** — jangan pernah query semua resi/invoice tanpa limit, terutama di dashboard admin yang datanya terus bertambah. Pakai `paginate(20)` bukan `get()`.
- Pakai eager loading untuk hindari N+1 query:
  ```php
  Resi::with(['user', 'masterShipment'])->paginate(20);
  ```
- Untuk laporan/summary (misal jumlah resi per status), pertimbangkan query agregat (`selectRaw`, `groupBy`) daripada loop di PHP.

### 14.3 Upload foto & file
- **Resize/compress foto sebelum disimpan** — foto asli dari HP admin bisa 5-8MB, kalikan ratusan resi bisa cepat habiskan storage & bandwidth shared hosting. Gunakan Intervention Image untuk resize max ±1200px width dan compress ke JPEG quality 75-80 sebelum simpan.
- Kalau storage hosting terbatas, pertimbangkan pindah upload foto ke object storage terpisah (S3-compatible seperti Cloudflare R2 — ada tier gratis yang cukup besar) daripada numpuk semua di disk shared hosting.
- Set validasi `max:2048` (KB) di form request untuk cegah upload file raksasa dari awal.

### 14.4 Caching
- Cache data yang jarang berubah tapi sering diakses: rate per kg terakhir, summary counter dashboard admin (jumlah resi per status), dsb.
- Driver `file` atau `database` cache cukup di tahap awal ini — belum perlu Redis/Memcached selama traffic masih skala ratusan user.
  ```php
  Cache::remember('dashboard-summary', now()->addMinutes(5), fn () => ...);
  ```

### 14.5 Rate limiting & keamanan resource
- Throttle route login (`throttle:5,1`) dan route webhook payment gateway, supaya tidak gampang di-spam/brute-force yang menghabiskan proses server.
- Validasi signature/token webhook Midtrans di awal handler, sebelum melakukan query apapun — request tidak valid harus di-reject secepat mungkin (fail fast), jangan sampai ikut membebani database.

### 14.6 Idempotency di webhook payment
- Midtrans/Xendit bisa mengirim notifikasi yang sama berkali-kali (retry adalah perilaku normal). Pastikan handler webhook **idempotent**: cek dulu apakah `payments.gateway_reference` sudah pernah diproses sebelum update status, supaya invoice/status tidak ter-update dobel atau email notifikasi tidak terkirim berulang.

### 14.7 Monitoring sederhana
- Aktifkan Laravel `Log` untuk mencatat job yang gagal (`failed_jobs` table bawaan queue), supaya kalau ada import/email yang gagal, admin bisa tahu dan retry manual — bukan cuma diam-diam hilang.
- Cek pemakaian resource cPanel (CPU/proses) secara berkala lewat menu "Resource Usage" hosting, supaya tahu kapan mulai mendekati limit sebelum benar-benar down.

### 14.8 Kapan waktunya pindah dari shared hosting
Tanda-tanda shared hosting sudah tidak cukup (bukan soal jumlah user total, tapi soal beban bersamaan):
- Situs mulai lambat/504 saat banyak admin & customer akses bersamaan di jam sibuk.
- Queue job sering menumpuk/telat diproses walau sudah pakai cron tiap menit.
- Storage upload foto sudah mendekati limit paket hosting.

Solusinya bukan optimasi kode lagi di titik ini, tapi migrasi ke **VPS** (DigitalOcean, AWS Lightsail, dsb) yang memberi kontrol penuh atas jumlah PHP-FPM worker, queue worker sungguhan (daemon via Supervisor), dan resource yang bisa di-scale sesuai kebutuhan.

---

Gunakan struktur ini sebagai kerangka awal — sesuaikan nama tabel/kolom kalau ada konvensi lain yang biasa kamu pakai di proyek Laravel STAIMAS sebelumnya.
