<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            // buat 1-2 order per user
            $order = Order::create([
                'user_id'    => $user->id, // ✅ ganti dari customer
                'invoice_id' => 'INV-' . strtoupper(Str::random(8)),
                'total'      => 0, // nanti dihitung dari items
                'status'     => 'baru',
                'order_date' => now(),
            ]);

            // ambil random 2 produk
            $items = $products->random(2);

            $total = 0;
            foreach ($items as $product) {
                $quantity = rand(1,3);
                $price = $product->price;
                $subtotal = $quantity * $price;
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'subtotal'   => $subtotal, // wajib ada karena column not null
                ]);
            }

            // update total order
            $order->update(['total' => $total]);
        }
    }
}
