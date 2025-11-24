<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    public $timestamps = false; // Tabel ini tidak butuh created_at/updated_at

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];
}
