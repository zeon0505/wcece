<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MasterShipment;
use App\Models\Resi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $counts = Resi::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentResis = Resi::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $pendingPayments = Invoice::where('status', 'pending_verification')->count();

        $activeShipments = MasterShipment::where('status', 'in_transit')->count();

        return view('admin.dashboard', compact('counts', 'recentResis', 'pendingPayments', 'activeShipments'));
    }
}
