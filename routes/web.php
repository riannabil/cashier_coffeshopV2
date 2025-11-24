<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\CategoryManagement;
use App\Livewire\SupplierManagement;
use App\Livewire\MenuManagement;
use Illuminate\Support\Facades\Route;
use App\Livewire\UserManagement;
use App\Livewire\ShiftManagement;
use App\Livewire\ScheduleManagement;
use App\Livewire\AttendancePage;
use App\Livewire\PosPage;
use App\Livewire\ReportPage;
use App\Livewire\SettingsPage;
use App\Livewire\AttendanceValidation;
use App\Livewire\PayrollPage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/attendance', AttendancePage::class)->name('attendance.index');
    Route::get('/pos', PosPage::class)->name('pos.index');

    // Halaman Profile (dipindah ke sini agar semua role bisa akses)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware(['auth', 'role:Admin|Manajer'])->group(function () {
        Route::get('/attendance-validation', AttendanceValidation::class)->name('attendance.validation');
    });
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/users', UserManagement::class)->name('users.index');
    Route::get('/categories', CategoryManagement::class)->name('categories.index');
    Route::get('/menus', MenuManagement::class)->name('menus.index');
    Route::get('/suppliers', SupplierManagement::class)->name('suppliers.index');
    Route::get('/shifts', ShiftManagement::class)->name('shifts.index');
    Route::get('/schedules', ScheduleManagement::class)->name('schedules.index');
    Route::get('/reports', ReportPage::class)->name('reports.index');
    Route::get('/settings', SettingsPage::class)->name('settings.index');
    Route::get('/payroll', PayrollPage::class)->name('payroll.index');
});

require __DIR__ . '/auth.php';
