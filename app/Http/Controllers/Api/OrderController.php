<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // GET /api/orders
    public function index()
    {
        return response()->json(Order::with('items.product')->get());
    }

    // POST /api/orders
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|string|max:255', // ✅ ganti
            'status' => 'required|string|in:baru,diproses,dikirim,selesai',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Hitung total dari items
        $total = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price']);

        // Buat order baru
        $order = Order::create([
            'invoice_id' => 'INV-' . strtoupper(Str::random(8)),
            'customer'   => $request->customer, // ✅ ganti
            'total'      => $total,
            'status'     => $request->status,
            'order_date' => $request->order_date,
        ]);

        // Simpan order items
        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['quantity'] * $item['price'], // ✅ tambah subtotal
            ]);
        }

        return response()->json($order->load('items.product'), 201);
    }

    // GET /api/orders/{id}
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return response()->json($order);
    }

    // PUT /api/orders/{id}
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'customer' => 'sometimes|string|max:255', // ✅ ganti
            'status' => 'sometimes|string|in:baru,diproses,dikirim,selesai',
            'order_date' => 'sometimes|date',
        ]);

        $order->update($request->only(['customer', 'status', 'order_date'])); // ✅ ganti

        return response()->json($order->load('items.product'));
    }

    // DELETE /api/orders/{id}
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete(); // hapus item dulu
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
