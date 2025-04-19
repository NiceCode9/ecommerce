<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistButton extends Component
{
    public $product;
    public $inWishlist;

    public function mount($product)
    {
        $this->product = $product;
        $this->inWishlist = $this->checkWishlist();
    }

    public function checkWishlist()
    {
        if (!Auth::check()) {
            return false;
        }

        return Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $this->product->id)
            ->exists();
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->inWishlist) {
            Wishlist::where('user_id', Auth::id())
                ->where('produk_id', $this->product->id)
                ->delete();
            $this->inWishlist = false;
            $this->emit('wishlistUpdated');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'produk_id' => $this->product->id
            ]);
            $this->inWishlist = true;
            $this->emit('wishlistUpdated');
        }
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
