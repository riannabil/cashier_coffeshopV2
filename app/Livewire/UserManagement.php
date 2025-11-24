<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role; // <-- 1. Import Role
use Illuminate\Support\Facades\Hash; // <-- 2. Import Hash
use Livewire\WithPagination;

#[Layout('layouts.app')] // Tentukan layout utama kita
class UserManagement extends Component
{
    use WithPagination;
    // Properti untuk menampung data form
    public $name;
    public $email;
    public $password;
    public $base_salary;
    public $role_id;
    public $roles;
    public $showModal = false;
    public $userId; // <-- Tambahkan ini
    public $confirmingDelete = false; // <-- dan ini
    public $search = '';
    public $perPage = 10; // jumlah data per halaman

    protected $paginationTheme = 'tailwind'; // untuk styling pagination

    /**
     * mount() dijalankan saat component di-load pertama kali.
     * Kita gunakan ini untuk mengisi dropdown role.
     */
    public function mount()
    {
        // Ambil semua role dari database (Admin, Manajer, Karyawan)
        $this->roles = Role::all();
    }

    /**
     * Fungsi ini akan dipanggil untuk membuka modal
     * dan membersihkan form.
     */
    public function openModal()
    {
        $this->reset(['name', 'email', 'password', 'base_salary', 'role_id']);
        $this->showModal = true;
    }

    /**
     * Fungsi ini untuk menutup modal.
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Fungsi ini akan dipanggil saat form di-submit.
     */
    public function saveUser()
    {
        // Langkah 1: Validasi data
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'base_salary' => 'required|numeric|min:0',
            'role_id' => 'required|exists:roles,id', // Pastikan role_id ada di tabel roles
        ]);

        // Langkah 2: Buat user baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password), // Enkripsi password
            'base_salary' => $this->base_salary,
        ]);

        // Langkah 3: Berikan role ke user baru
        $role = Role::findById($this->role_id);
        $user->assignRole($role);

        $this->dispatch('user-created', message: 'User baru berhasil di edit!');
        // Langkah 4: Reset form dan tutup modal
        $this->closeModal();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        // isi form dengan data user yang ingin diedit
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->base_salary = $user->base_salary;
        $this->role_id = $user->roles->first()?->id ?? null;

        // password tidak diisi (tidak wajib saat edit)
        $this->password = '';

        $this->showModal = true;
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'base_salary' => 'required|numeric|min:0',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'base_salary' => $this->base_salary,
            // hanya update password kalau diisi
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        $this->dispatch('user-updated', message: 'User baru berhasil di edit!');
        // update role
        $role = Role::findById($this->role_id);
        $user->syncRoles([$role]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->userId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userId);
        $user->delete();

        $this->confirmingDelete = false;

        // Kirim event ke browser
        $this->dispatch('user-deleted', message: 'User berhasil dihapus!');
    }

    public function updatingSearch()
    {
        $this->resetPage(); // reset halaman saat search berubah
    }


    /**
     * Fungsi render() akan mengambil data terbaru dan menampilkan view.
     */
    public function render()
    {
        // $users = User::all();
        $users = User::where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->paginate($this->perPage);
        return view('livewire.user-management', [
            'users' => $users
        ]);
    }
}
