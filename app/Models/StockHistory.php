<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    // Matikan timestamps otomatis agar tidak error mencari 'updated_at'
    public $timestamps = false;

    protected $guarded = [];

    // Agar created_at terisi otomatis saat create, kita bisa boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }
}
