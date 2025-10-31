<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLogs;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Container\Attributes\Auth;

class manajemenStockBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manajemenStockBarang.index');
    }

    public function getProducts(Request $request)
    {
        try {
            // $id_user = $request->input('id_user');
            $dataProduct = Product::with('supplier', 'stockHistories')->get();

            if (!$dataProduct) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                    'dataProduct' => null,
                    'kategori' => [],
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil Fetch Product',
                'dataProduct' => $dataProduct,
                'kategori' => $dataProduct->pluck('category')->unique()->filter()->values(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Get Tahun Ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ CREATE
    public function insertProduct(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'name' => 'required|string|max:255',
                'category' => 'nullable|string|max:255',
                'stock' => 'required|integer|min:0',
                'unit' => 'nullable|string|max:50',
            ]);

            $product = Product::create($validatedData);

            ProductLogs::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'action' => 'created',
                'description' => 'Menambahkan product ' . $product->name,
            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan Product',
                'dataProduct' => $product
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Menambahkan Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ READ (By ID)
    public function getProductById(Request $request)
    {
        try {
            $id = $request->input('id');
            $product = Product::with(['supplier', 'stockHistories'])->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                    'dataProduct' => [],
                    'kategori' => [],
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil Fetch Detail Product',
                'dataProduct' => $product,
                'kategori' => $product->pluck('category')->unique()->filter()->values(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Fetch Detail Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ UPDATE
    public function updateProduct(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'supplier_id' => 'sometimes|exists:suppliers,id',
                'name' => 'sometimes|string|max:255',
                'category' => 'sometimes|string|max:255',
                'stock' => 'sometimes|integer|min:0',
                'unit' => 'sometimes|string|max:50',
            ]);

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                    'dataProduct' => null
                ], 404);
            }

            $product->update($validatedData);

            ProductLogs::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'action' => 'updated',
                'description' => 'Update product ' . $product->name,
            ]);

            return response()->json([
                'message' => 'Berhasil Update Product',
                'dataProduct' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Update Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // ✅ DELETE
    public function deleteProduct($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                    'dataProduct' => null
                ], 404);
            }

            $product->delete();

            ProductLogs::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'action' => 'delete',
                'description' => 'Hapus product ' . $product->name,
            ]);

            return response()->json([
                'message' => 'Berhasil Hapus Product',
                'dataProduct' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Hapus Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSuppliers(Request $request)
    {
        try {
            $dataSuppliers = Supplier::get();

            if (!$dataSuppliers) {
                return response()->json([
                    'message' => 'Suppliers tidak ditemukan',
                    'dataSuppliers' => null,
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil Fetch Suppliers',
                'dataSuppliers' => $dataSuppliers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Get Tahun Ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
