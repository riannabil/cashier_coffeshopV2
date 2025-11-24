<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockHistory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class PosPage extends Component
{
    // Data Master
    public $categories;
    public $menus;

    // Filter
    public $selectedCategory = 'all';
    public $search = '';

    // Cart (Keranjang)
    // Struktur: [menu_id => ['id', 'name', 'price', 'qty', 'note']]
    public $cart = [];

    // Hitungan
    public $totalAmount = 0;

    public function mount()
    {
        $this->categories = Category::all();
        $this->loadMenus();
    }

    // Load menu berdasarkan filter
    public function loadMenus()
    {
        $query = Menu::query()->where('status', 'Ready'); // Hanya menu yang Ready

        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->menus = $query->get();
    }

    // Saat filter berubah, reload menu
    public function updatedSelectedCategory()
    {
        $this->loadMenus();
    }
    public function updatedSearch()
    {
        $this->loadMenus();
    }

    // --- LOGIKA CART ---

    public function addToCart($menuId)
    {
        $menu = Menu::find($menuId);

        // Cek stok (Opsional: jika ingin ketat)
        if ($menu->stock <= 0) {
            session()->flash('error', 'Stok habis!');
            return;
        }

        // Jika sudah ada di cart, tambah qty
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['qty']++;
        } else {
            // Jika belum, masukkan item baru
            $this->cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'qty' => 1,
                'note' => '' // Catatan (modifiers)
            ];
        }

        $this->calculateTotal();
    }

    public function increaseQty($menuId)
    {
        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['qty']++;
            $this->calculateTotal();
        }
    }

    public function decreaseQty($menuId)
    {
        if (isset($this->cart[$menuId])) {
            if ($this->cart[$menuId]['qty'] > 1) {
                $this->cart[$menuId]['qty']--;
            } else {
                unset($this->cart[$menuId]); // Hapus jika qty jadi 0
            }
            $this->calculateTotal();
        }
    }

    public function removeItem($menuId)
    {
        unset($this->cart[$menuId]);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = 0;
        foreach ($this->cart as $item) {
            $this->totalAmount += $item['price'] * $item['qty'];
        }
    }

    // --- LOGIKA CHECKOUT (TRANSAKSI) ---
    public function checkout()
    {
        if (empty($this->cart)) return;

        // Gunakan Database Transaction agar aman
        DB::transaction(function () {
            // 1. Buat Order Baru
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_type' => 'Dine-In', // Default dulu, nanti bisa dibuat dinamis
                'total_amount' => $this->totalAmount,
                'status' => 'Paid', // Langsung lunas (sesuai request manual)
            ]);

            // 2. Simpan Detail Item & Kurangi Stok
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price_at_sale' => $item['price'],
                    'notes' => $item['note']
                ]);

                // Kurangi Stok Menu
                $menu = Menu::find($item['id']);
                $menu->decrement('stock', $item['qty']);

                // Catat History Stok
                StockHistory::create([
                    'menu_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'change_amount' => - ($item['qty']), // Negatif karena keluar
                    'last_stock' => $menu->stock + $item['qty'],
                    'current_stock' => $menu->stock,
                    'type' => 'Sale',
                    'description' => 'Penjualan Order #' . $order->id
                ]);
            }
        });

        // Reset Cart setelah sukses
        $this->cart = [];
        $this->totalAmount = 0;
        $this->loadMenus(); // Refresh data menu (stok baru)

        session()->flash('message', 'Transaksi Berhasil!');
    }

    public function render()
    {
        return view('livewire.pos-page');
    }
}
