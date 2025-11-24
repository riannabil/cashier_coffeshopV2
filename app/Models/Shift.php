<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    public $timestamps = false; // Tabel shifts tidak punya timestamps

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
}
