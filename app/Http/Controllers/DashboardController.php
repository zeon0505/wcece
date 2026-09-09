<?php

namespace App\Http\Controllers;

use App\Models\Resi;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $query = $request->user()->resis()
            ->with(['masterShipment', 'statusHistories'])
            ->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $resis = $query->paginate(20)->withQueryString();

        $counts = $request->user()->resis()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $unpaidInvoicesCount = $request->user()->invoices()->where('status', 'unpaid')->count();

        // Get invoices keyed by master_shipment_id for payment status lookup
        $invoicesByShipment = $request->user()->invoices()
            ->get()
            ->keyBy('master_shipment_id');

        return view('customer.dashboard', compact('resis', 'counts', 'statusFilter', 'unpaidInvoicesCount', 'invoicesByShipment'));
    }
}
