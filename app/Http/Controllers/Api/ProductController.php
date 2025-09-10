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

        // tambahkan photo_url untuk frontend
        $products->transform(function ($item) {
            $item->photo = $item->photo_url;
            return $item;
        });

        return response()->json($products);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with(['category'])->findOrFail($id);
        $product->photo = $product->photo_url;

        return response()->json($product);
    }

    public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'status'      => 'required|in:publish,draft',
        'category_id' => 'required|exists:categories,id',
        'price'       => 'required|numeric',
        'weight'      => 'nullable|numeric',
        'photo'       => 'nullable|image|max:2048', // tambahkan rule image
    ]);

    $data = $request->only(['name', 'description', 'status', 'category_id', 'price', 'weight']);

    // Upload gambar jika ada
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('products', 'public');
        $data['photo'] = $path;
    }

    $product = Product::create($data);
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
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'status', 'category_id', 'price', 'weight']);

        if ($request->hasFile('photo')) {
    // hapus foto lama jika ada
    if ($product->photo && file_exists(storage_path('app/public/' . $product->photo))) {
        unlink(storage_path('app/public/' . $product->photo));
    }

    $file = $request->file('photo');
    $filename = time() . '_' . $file->getClientOriginalName();

    // simpan di folder products agar konsisten
    $path = $file->storeAs('products', $filename, 'public');

    $data['photo'] = $path;
}


        $product->update($data);
        $product->photo = $product->photo_url;

        return response()->json($product);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // hapus file foto jika ada
        if ($product->photo && file_exists(storage_path('app/public/' . $product->photo))) {
            unlink(storage_path('app/public/' . $product->photo));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
