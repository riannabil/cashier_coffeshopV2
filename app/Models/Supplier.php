<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
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
        'name',
        'contact_person',
        'phone',
        'address',
    ];
}
