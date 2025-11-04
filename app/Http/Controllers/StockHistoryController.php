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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);
        $logs->appends($request->all());

        return view('productLogs.index', compact('logs'));
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
