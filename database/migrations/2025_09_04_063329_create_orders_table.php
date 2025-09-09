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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (siapa yang buat order)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('invoice_id')->unique(); // kode invoice
            $table->decimal('total', 15, 2)->default(0); // total order
            $table->enum('status', ['baru', 'diproses', 'dikirim', 'selesai'])
                  ->default('baru');
            $table->timestamp('order_date')->useCurrent(); // tanggal order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
