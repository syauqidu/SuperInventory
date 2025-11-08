<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockHistory;
use App\Models\ProductLogs;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();

        $totalStock = Product::sum('stock');
        $lowStockCount = Product::where('stock', '<=', 20)->count();

        // $recentLogs = ProductLogs::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentLogs = ProductLogs::latest()->take(5)->get();

        $latestActivities = ProductLogs::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalStock',
            'lowStockCount',
            'recentLogs',
            'latestActivities'
        ));
    }
}
