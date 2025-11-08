<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLogs;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class manajemenStockBarangController extends Controller
{
    public function index()
    {
        return view('manajemenStockBarang.index');
    }

    public function getProducts()
    {
        try {
            $products = app(Product::class)::with('supplier')->get();


            if ($products->isEmpty()) {
                return response()->json(['message' => 'Tidak ada product'], 404);
            }

            return response()->json([
                'message' => 'Berhasil Get Product',
                'dataProduct' => $products,
                'kategori' => $products->pluck('category')->unique()->filter()->values(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Get Tahun Ajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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

            $product = app(Product::class)->create($validatedData);

            return response()->json([
                'message' => 'Berhasil Menambahkan Product',
                'dataProduct' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menambahkan Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
                'kategori' => collect([$product->category])->filter()->values(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal Fetch Detail Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'stock' => 'sometimes|integer|min:0',
                'name' => 'sometimes|string|max:255',
                'category' => 'nullable|string|max:255',
            ]);

            $product = Product::find($id);

            ProductLogs::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'action' => 'created',
                'description' => 'Menambahkan product ' . $product->name,
            ]);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                    'dataProduct' => null
                ], 404);
            }

            $changes = [];
            foreach ($validatedData as $key => $value) {
                if ($product->{$key} != $value) {
                    $changes[$key] = ['from' => $product->{$key}, 'to' => $value];
                }
            }
            $product->update($validatedData);

            $description = 'Update product ' . $product->name;
            if (!empty($changes)) {
                $description .= ' (Changes: ' . json_encode($changes) . ')';
            }

            ProductLogs::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'action' => 'updated',
                'description' => $description,
            ]);

            return response()->json([
                'message' => 'Berhasil Update Product',
                'dataProduct' => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Update Product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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

            ProductLogs::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'action' => 'delete',
                'description' => 'Hapus product ' . $product->name,
            ]);

            $product->delete();

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

            if ($dataSuppliers->isEmpty()) {
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
