<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        $products = Product::with('category')->get();

        // Ganti 'photo' dengan 'photo_url' agar frontend bisa menampilkan gambar
        $products->transform(function ($item) {
            $item->photo = $item->photo_url;
            return $item;
        });

        return response()->json($products);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with(['category', 'orderItems'])->findOrFail($id);

        // Gunakan photo_url untuk frontend
        $product->photo = $product->photo_url;

        return response()->json($product);
    }

    // POST /api/products
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:publish,draft',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'weight'      => 'nullable|numeric',
            'photo'       => 'nullable|string',
        ]);

        $product = Product::create($request->only([
            'name', 'description', 'status', 'category_id', 'price', 'weight', 'photo'
        ]));

        // Kembalikan photo_url
        $product->photo = $product->photo_url;

        return response()->json($product, 201);
    }

    // PUT /api/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status'      => 'sometimes|in:publish,draft',
            'category_id' => 'sometimes|exists:categories,id',
            'price'       => 'sometimes|numeric',
            'weight'      => 'sometimes|numeric',
            'photo'       => 'sometimes|string',
        ]);

        $product->update($request->only([
            'name', 'description', 'status', 'category_id', 'price', 'weight', 'photo'
        ]));

        $product->photo = $product->photo_url;

        return response()->json($product);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
