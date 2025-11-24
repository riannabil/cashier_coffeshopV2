<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'shift_id',
        'date',
    ];

    // Relasi ke User (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
