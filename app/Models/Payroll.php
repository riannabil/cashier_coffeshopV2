<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    public $timestamps = false; // Tidak butuh timestamps (tapi kita bisa pakai created_at manual jika mau)

    protected $fillable = [
        'user_id',
        'period', // Format: "Y-m" (Contoh: "2025-11")
        'base_salary',
        'late_deduction_total',
        'alpha_deduction_total',
        'final_salary',
        'status', // 'Calculated', 'Paid'
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
