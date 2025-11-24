<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Models\Category; // <-- 1. Kita butuh ini untuk dropdown
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MenuManagement extends Component
{
    use WithPagination;

    // Properti untuk form
    public $name;
    public $category_id;
    public $price;
    public $stock;
    public $status;
    public $menuId;

    // Properti untuk UI
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;

    // Properti untuk dropdown
    public $categories;

    protected $paginationTheme = 'tailwind';

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:menus,name,' . $this->menuId,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:Ready,Sold Out', // Sesuaikan dengan nilai di DB Anda
        ];
    }

    // Ambil data kategori saat component di-load
    public function mount()
    {
        $this->categories = Category::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Membuka modal untuk "Tambah Menu"
    public function openModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->status = 'Ready'; // Default status saat buat baru
        $this->showModal = true;
    }

    // Menutup modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Membersihkan form
    public function resetForm()
    {
        $this->reset(['name', 'category_id', 'price', 'stock', 'status', 'menuId', 'isEditing']);
    }

    // Fungsi utama: Dipanggil saat 'Save' atau 'Update'
    public function save()
    {
        $validatedData = $this->validate(); // Validasi

        if ($this->isEditing) {
            // Logika Update
            $menu = Menu::find($this->menuId);
            if ($menu) {
                $menu->update($validatedData);
            }
        } else {
            // Logika Create
            Menu::create($validatedData);
        }

        $this->closeModal();
    }

    // Logika untuk masuk mode Edit
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->menuId = $id;
        $this->name = $menu->name;
        $this->category_id = $menu->category_id;
        $this->price = $menu->price;
        $this->stock = $menu->stock;
        $this->status = $menu->status;

        $this->isEditing = true;
        $this->showModal = true;
    }

    // Logika untuk menghapus
    public function delete($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menu->delete();
        }
    }

    // Fungsi render() untuk mengambil data dan menampilkan view
    public function render()
    {
        $menus = Menu::with('category') // Ambil relasi category
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.menu-management', [
            'menus' => $menus
        ]);
    }
}
