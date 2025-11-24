<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Kita buka semua kolom agar bisa diisi (Order::create)
    protected $guarded = [];

    // Relasi: Order punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order milik User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
