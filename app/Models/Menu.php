<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * Beritahu Eloquent untuk TIDAK menggunakan kolom timestamps.
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi melalui form.
     */
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'low_stock_threshold',
        'status',
    ];

    /**
     * Definisikan relasi ke tabel Category.
     * Satu Menu 'dimiliki oleh' satu Kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
