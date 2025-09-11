<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderItemController extends Controller
{
    // ============================
    // Simpan order beserta items
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Hitung total
        $total = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $total += $product->price * $item['quantity'];
        }

        // Buat order
        $order = Order::create([
            'user_id' => $request->user_id,
            'invoice_id' => 'INV-' . Str::upper(Str::random(8)),
            'total' => $total,
            'status' => 'baru',
            'order_date' => now(),
        ]);

        // Simpan order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity'],
            ]);
        }

        // Return order beserta items
        $order->load('items.product', 'user');

        return response()->json($order, 201);
    }

    // ============================
    // Tampilkan semua order items
    // ============================
    public function index()
    {
        $items = OrderItem::with('product', 'order.user')->get();
        return response()->json($items);
    }

    // ============================
    // Tampilkan detail order item
    // ============================
    public function show($id)
    {
        $item = OrderItem::with('product', 'order.user')->find($id);
        if (!$item) {
            return response()->json(['message' => 'Order item tidak ditemukan'], 404);
        }
        return response()->json($item);
    }
}
