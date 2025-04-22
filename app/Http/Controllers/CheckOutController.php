<?php

namespace App\Http\Controllers;

use App\Http\Services\RajaOngkirService;
use App\Models\Alamat;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckOutController extends Controller
{
    public function index()
    {
        $cartIds = request('cart_ids', []); // Dapatkan dari query string

        // Validasi cart_ids
        if (empty($cartIds)) {
            return redirect()->route('pelanggan.cart.index')->with('error', 'Silakan pilih produk untuk checkout');
        }

        $user = auth()->user();

        // Ambil item cart yang dipilih
        $cartItems = $user->carts()
            ->whereIn('id', $cartIds)
            ->with('produk')
            ->get();

        // Hitung subtotal dan total berat
        $subtotal = $cartItems->sum(function ($item) {
            return $item->jumlah * $item->produk->harga_setelah_diskon;
        });

        $totalWeight = $cartItems->sum(function ($item) {
            return $item->jumlah * $item->produk->berat;
        });

        $totalItems = $cartItems->sum('jumlah');

        // Ambil daftar alamat user
        // $alamats = $user->alamat()->orderBy('is_utama', 'desc')->get();

        return view('front.checkout.checkout', compact(
            'cartItems',
            'cartIds',
            'subtotal',
            'totalWeight',
            'totalItems',
            // 'alamats',
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id,user_id,' . auth()->id()
        ]);

        // Ambil data cart yang dipilih
        $carts = Keranjang::with('produk')
            ->whereIn('id', $request->cart_ids)
            ->where('user_id', auth()->id())
            ->get();

        // Hitung total berat (untuk ongkir)
        $totalWeight = $carts->sum(function ($cart) {
            return $cart->jumlah * $cart->produk->berat;
        });

        // Ambil alamat utama user
        $alamat = Alamat::where('pengguna_id', auth()->id())
            ->where('is_utama', true)
            ->firstOrFail();

        // Hitung ongkir via RajaOngkir
        $ongkir = $this->hitungOngkir($alamat->city_id, $totalWeight);

        // Simpan pesanan ke database
        $pesanan = $this->buatPesanan($carts, $alamat, $ongkir);

        // Redirect ke pembayaran Midtrans
        return $this->prosesPembayaran($pesanan);
    }
}
