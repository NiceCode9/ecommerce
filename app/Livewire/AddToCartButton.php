<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;

class AddToCartButton extends Component
{
    public $product;
    public $quantity = 1;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItem = Keranjang::where('pengguna_id', Auth::id())
            ->where('produk_id', $this->product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'jumlah' => $cartItem->quantity + $this->quantity
            ]);
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'produk_id' => $this->product->id,
                'jumlah' => $this->quantity
            ]);
        }

        $this->emit('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
