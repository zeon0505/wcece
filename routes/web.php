<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResiController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// ── Landing page & Public Information ─────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/syarat-ketentuan', function () {
    return view('tnc');
})->name('tnc');

Route::get('/kebijakan-privasi', function () {
    return view('privacy');
})->name('privacy');

// ── Auth routes (Breeze) ─────────────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Customer portal ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alamat', function () {
        $settings = \App\Http\Controllers\Admin\SettingsController::getAll();
        return view('customer.address.index', compact('settings'));
    })->name('address.index');

    Route::get('/resi/create', [ResiController::class, 'create'])->name('resi.create');
    Route::post('/resi', [ResiController::class, 'store'])->name('resi.store');
    Route::get('/resi/{resi}', [ResiController::class, 'show'])->name('resi.show');
    Route::post('/resi/{resi}/confirm-received', [ResiController::class, 'confirmReceived'])->name('resi.confirm-received');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/manual-proof', [InvoiceController::class, 'manualProof'])->name('invoices.manual-proof');

    Route::get('/track', [TrackController::class, 'index'])->name('track.index');
    Route::post('/track/{resi}/claim', [TrackController::class, 'claim'])->name('track.claim');
    
    // Unclaimed Resi routes
    Route::get('/track/{resi}/claim-form', [TrackController::class, 'claimForm'])->name('track.claim.form');
    Route::post('/track/{resi}/claim-submit', [TrackController::class, 'claimSubmit'])->name('track.claim.submit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin portal ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/resis', [Admin\ResiController::class, 'index'])->name('resis.index');
    Route::get('/resis/received', [Admin\ResiController::class, 'receivedHistory'])->name('resis.received');
    Route::get('/resis/create', [Admin\ResiController::class, 'create'])->name('resis.create');
    Route::post('/resis', [Admin\ResiController::class, 'store'])->name('resis.store');
    Route::get('/resis/export', [Admin\ResiController::class, 'export'])->name('resis.export');
    Route::get('/resis/{resi}/edit', [Admin\ResiController::class, 'edit'])->name('resis.edit');
    Route::put('/resis/{resi}', [Admin\ResiController::class, 'update'])->name('resis.update');
    Route::delete('/resis/{resi}', [Admin\ResiController::class, 'destroy'])->name('resis.destroy');
    Route::get('/resis/{resi}', [Admin\ResiController::class, 'show'])->name('resis.show');
    Route::post('/resis/{resi}/status', [Admin\ResiController::class, 'updateStatus'])->name('resis.status');
    Route::post('/resis/{resi}/photo', [Admin\ResiController::class, 'uploadPhoto'])->name('resis.photo');

    Route::get('/import', [Admin\ImportController::class, 'index'])->name('import.index');
    Route::post('/import/preview', [Admin\ImportController::class, 'preview'])->name('import.preview');
    Route::get('/import/preview', [Admin\ImportController::class, 'showPreview'])->name('import.preview.show');
    Route::post('/import/confirm', [Admin\ImportController::class, 'confirm'])->name('import.confirm');
    Route::get('/import/template', [Admin\ImportController::class, 'template'])->name('import.template');
    Route::get('/import/logs', [Admin\ImportController::class, 'logs'])->name('import.logs');

    Route::get('/shipments', [Admin\ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [Admin\ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [Admin\ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{shipment}', [Admin\ShipmentController::class, 'show'])->name('shipments.show');
    Route::post('/shipments/{shipment}/arrived', [Admin\ShipmentController::class, 'markArrived'])->name('shipments.markArrived');
    Route::post('/shipments/{shipment}/status', [Admin\ShipmentController::class, 'updateStatus'])->name('shipments.update_status');
    Route::post('/shipments/{shipment}/add-resis', [Admin\ShipmentController::class, 'addResis'])->name('shipments.addResis');
    Route::post('/shipments/{shipment}/generate-invoices', [Admin\ShipmentController::class, 'generateInvoices'])->name('shipments.generate-invoices');
    Route::post('/shipments/{shipment}/resis/{resi}/move', [Admin\ShipmentController::class, 'moveResi'])->name('shipments.move-resi');
    Route::post('/shipments/{shipment}/photo-box', [Admin\ShipmentController::class, 'uploadBoxPhoto'])->name('shipments.photo-box');

    Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/history', [Admin\PaymentController::class, 'history'])->name('payments.history');
    Route::get('/payments/history/{payment}', [Admin\PaymentController::class, 'historyDetail'])->name('payments.history.detail');
    Route::post('/payments/{payment}/verify', [Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::get('/payments/{payment}/verify', fn() => redirect()->route('admin.payments.index'));
    Route::post('/payments/{payment}/reject', [Admin\PaymentController::class, 'reject'])->name('payments.reject');
    Route::get('/payments/{payment}/reject', fn() => redirect()->route('admin.payments.index'));

    Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

    Route::get('/claims', [Admin\ResiClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/{claim}', [Admin\ResiClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims/{claim}/approve', [Admin\ResiClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/claims/{claim}/reject', [Admin\ResiClaimController::class, 'reject'])->name('claims.reject');

    // User Management (Approval)
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [Admin\UserController::class, 'reject'])->name('users.reject');
});
