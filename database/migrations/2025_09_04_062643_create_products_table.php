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
        Schema::create('products', function (Blueprint $table) {
        $table->id(); // id product
        $table->string('name'); // nama product
        $table->text('description')->nullable(); // deskripsi
        $table->enum('status', ['publish', 'draft'])->default('draft'); // status
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); // relasi ke category
        $table->decimal('price', 15, 2); // harga
        $table->decimal('weight', 8, 2)->nullable(); // berat
        $table->string('photo')->nullable(); // foto product
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
