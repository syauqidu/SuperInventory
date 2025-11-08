<?php

namespace App\Http\Controllers;

use App\Models\ProductLogs;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductLogs::with(['user', 'product']);

        // 🔍 Search by user name or product name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // ⚙️ Filter by action (created / updated / deleted)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 👤 Filter by specific user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 📅 Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);
        $logs->appends($request->all());

        // Pass users list to the view for the dropdown filter
        $users = \App\Models\User::all();

        return view('productLogs.index', compact('logs', 'users'));
    }


    /**
     * Search for product or username.
     */
    public function search(Request $request)
    {
        $query = ProductLogs::with(['user', 'product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $logs = $query->latest()->take(10)->get();

        return view('productLogs.partials.table', compact('logs')); // partial table
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockHistory $stockHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockHistory $stockHistory)
    {
        //
    }
}
