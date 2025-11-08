<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        $supplierCount = $suppliers->count();

        return view("suppliers.index", compact("suppliers", "supplierCount"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:100",
            "contact" => "required|string|max:50",
            "address" => "nullable|string|max:255",
        ]);

        Supplier::create($request->all());

        return redirect()
            ->route("suppliers.index")
            ->with("success", "Supplier created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view("suppliers.show", compact("supplier"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view("suppliers.edit", compact("supplier"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            "name" => "required|string|max:100",
            "contact" => "required|string|max:50",
            "address" => "nullable|string|max:255",
        ]);

        $supplier->update($request->all());

        return redirect()
            ->route("suppliers.index")
            ->with("success", "Supplier updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route("suppliers.index")
            ->with("success", "Supplier deleted successfully.");
    }
}
