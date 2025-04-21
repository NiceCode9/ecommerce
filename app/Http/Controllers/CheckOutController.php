<?php

namespace App\Http\Controllers;

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

        // Ambil daftar alamat user
        $alamats = $user->alamat()->orderBy('is_utama', 'desc')->get();

        return view('front.checkout.checkout', compact(
            'cartItems',
            'cartIds',
            'subtotal',
            'totalWeight',
            'alamats'
        ));
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'city_id' => 'required|numeric',
            'courier' => 'required|in:jne,tiki,pos',
            'weight' => 'required|numeric|min:1'
        ]);

        try {
            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.api_key')
            ])->post('https://api.rajaongkir.com/starter/cost', [
                'origin' => config('services.rajaongkir.origin_city_id'),
                'destination' => $request->city_id,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            $result = $response->json();

            if ($result['rajaongkir']['status']['code'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $result['rajaongkir']['status']['description']
                ]);
            }

            $services = $result['rajaongkir']['results'][0]['costs'];

            return response()->json([
                'success' => true,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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
