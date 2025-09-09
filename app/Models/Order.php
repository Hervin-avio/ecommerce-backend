<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',   // ✅ ganti dari 'customer'
        'total',
        'status',   // baru, diproses, dikirim, selesai
        'order_date'
    ];

    // Relasi: Order punya banyak OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Hitung total otomatis dari items (opsional)
    public function getGrandTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->price);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
