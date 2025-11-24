<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination; // <-- Untuk Pagination
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Tentukan layout utama kita
class CategoryManagement extends Component
{
    use WithPagination; // Aktifkan pagination

    // Properti untuk form
    public $name;
    public $categoryId;

    // Properti untuk kontrol UI
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;

    // Set tema pagination ke tailwind
    protected $paginationTheme = 'tailwind';

    // Aturan validasi (dibuat terpisah agar rapi)
    protected function rules()
    {
        return [
            // 'name' harus unik, KECUALI untuk ID-nya sendiri saat edit
            'name' => 'required|string|min:3|max:255|unique:categories,name,' . $this->categoryId,
        ];
    }

    // Panggil ini saat $search diubah, untuk reset halaman ke 1
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Panggil ini saat $perPage diubah, untuk reset halaman ke 1
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Membuka modal untuk "Tambah Kategori"
    public function openModal()
    {
        $this->resetForm();
        $this->isEditing = false;
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
        $this->reset(['name', 'categoryId', 'isEditing']);
    }

    // Fungsi utama: Dipanggil saat 'Save' atau 'Update'
    public function save()
    {
        $this->validate(); // Validasi 'name'

        if ($this->isEditing) {
            // Logika Update
            $category = Category::find($this->categoryId);
            if ($category) {
                $category->update(['name' => $this->name]);
            }
        } else {
            // Logika Create
            Category::create(['name' => $this->name]);
        }

        $this->closeModal();
    }

    // Logika untuk masuk mode Edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->isEditing = true;
        $this->showModal = true;
    }

    // Logika untuk menghapus
    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
        // Tabel akan otomatis refresh
    }

    // Fungsi render() untuk mengambil data dan menampilkan view
    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.category-management', [
            'categories' => $categories
        ]);
    }
}
