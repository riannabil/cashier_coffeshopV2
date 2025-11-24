<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Tabel ini PUNYA timestamps (created_at, updated_at) jadi biarkan default

    protected $fillable = [
        'user_id',
        'schedule_id',
        'clock_in',
        'clock_out',
        'late_minutes',
        'status', // 'On-Time', 'Late', 'Alpha', 'Sakit', 'Izin'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
