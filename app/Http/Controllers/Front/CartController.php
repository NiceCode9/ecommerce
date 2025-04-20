<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;

class CartController extends Controller
{
    public function index(){
        return view('front.cart.cart');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);
    
        $cart = Keranjang::where('pengguna_id', auth()->id())
            ->where('produk_id', $request->product_id)
            ->first();
    
        if ($cart) {
            $cart->update([
                'jumlah' => $cart->jumlah + ($request->quantity ?? 1)
            ]);
        } else {
            Keranjang::create([
                'pengguna_id' => auth()->id(),
                'produk_id' => $request->product_id,
                'jumlah' => $request->quantity ?? 1
            ]);
        }
    
        // Ambil data cart terbaru untuk diupdate
        $cartItems = auth()->user()->carts()->with('produk.gambarUtama')->latest()->take(3)->get();
        $cartCount = auth()->user()->carts()->count();
        $subtotal = auth()->user()->carts()->with('produk')->get()->sum(function($item) {
            return $item->jumlah * $item->produk->harga_setelah_diskon;
        });
    
        return response()->json([
            'status' => 'success',
            'count' => auth()->user()->carts()->sum('jumlah'),
            'html' => view('front.partials.cart-items', [
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
                'subtotal' => $subtotal
            ])->render()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
    
        $cart = Keranjang::where('pengguna_id', auth()->id())
            ->findOrFail($id);
    
        $cart->update(['jumlah' => $request->quantity]);
    
        return response()->json([
            'status' => 'success',
            'total_item' => auth()->user()->carts()->sum('jumlah'),
            'subtotal' => number_format($cart->jumlah * $cart->produk->harga_setelah_diskon, 0, ',', '.'),
            'total' => number_format(auth()->user()->carts()->with('produk')->get()->sum(function($item) {
                return $item->jumlah * $item->produk->harga_setelah_diskon;
            }), 0, ',', '.')
        ]);
    }

    public function remove($id)
    {
        $cart = Keranjang::where('pengguna_id', auth()->id())
            // ->where('produk_id', $id)
            // ->firstOrFail();
            ->findOrFail($id);

        $cart->delete();

        // if ($cart->jumlah > 1) {
        //     $cart->update(['jumlah' => $cart->jumlah - 1]);
        // } else {
        //     $cart->delete();
        // }

        // Ambil data terbaru setelah delete/update
        $cartItems = auth()->user()->carts()->with('produk.gambarUtama')->latest()->take(3)->get();
        $cartCount = auth()->user()->carts()->count();
        $subtotal = auth()->user()->carts()->with('produk')->get()->sum(function($item) {
            return $item->jumlah * $item->produk->harga_setelah_diskon;
        });

        return response()->json([
            'status' => 'success',
            'count' => auth()->user()->carts()->sum('jumlah'),
            'html' => view('front.partials.cart-items', [
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
                'subtotal' => $subtotal
            ])->render()
        ]);
    }
}
