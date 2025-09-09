<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // relasi ke order
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // relasi ke product
        $table->integer('quantity'); // jumlah barang
        $table->decimal('price', 15, 2); // harga per unit saat order
        $table->decimal('subtotal', 15, 2); // total per item (qty * price)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
