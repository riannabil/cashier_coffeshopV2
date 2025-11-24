<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SupplierManagement extends Component
{
    use WithPagination;

    // Properti untuk form
    public $name;
    public $contact_person;
    public $phone;
    public $address;
    public $supplierId;

    // Properti untuk kontrol UI
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:suppliers,name,' . $this->supplierId,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Membuka modal untuk "Tambah"
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
        $this->reset(['name', 'contact_person', 'phone', 'address', 'supplierId', 'isEditing']);
    }

    // Fungsi utama: Dipanggil saat 'Save' atau 'Update'
    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            // Logika Update
            $supplier = Supplier::find($this->supplierId);
            if ($supplier) {
                $supplier->update($validatedData);
            }
        } else {
            // Logika Create
            Supplier::create($validatedData);
        }

        $this->closeModal();
    }

    // Logika untuk masuk mode Edit
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;

        // ▼▼▼ INI ADALAH PERBAIKANNYA ▼▼▼
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;

        $this->isEditing = true;
        $this->showModal = true;
        // ▲▲▲ AKHIR PERBAIKAN ▲▲▲
    }

    // Logika untuk menghapus
    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
        }
    }

    // Fungsi render() untuk mengambil data dan menampilkan view
    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('contact_person', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.supplier-management', [
            'suppliers' => $suppliers
        ]);
    }
}
