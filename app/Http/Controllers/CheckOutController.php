<?php

namespace App\Http\Controllers;

use App\Http\Services\RajaOngkirService;
use App\Models\Alamat;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

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
        // dd($request->all());
        $request->validate([
            'cart_ids' => 'required',
            'alamat_id' => 'required|exists:alamat,id,pengguna_id,' . auth()->id(),
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,midtrans'
        ]);

        DB::beginTransaction();
        try {
            $cartIds = json_decode($request->cart_ids, true);
            // 1. Get Cart Items
            $carts = auth()->user()->carts()
                ->whereIn('id', $cartIds)
                ->with('produk')
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception("Keranjang belanja kosong");
            }

            // 2. Create Order
            $pesanan = Pesanan::create([
                'nomor_pesanan' => 'INV-' . time() . '-' . auth()->id(),
                'pengguna_id' => auth()->id(),
                'alamat_pengiriman_id' => $request->alamat_id,
                'metode_pengiriman' => $request->shipping_name, // Default, bisa disesuaikan
                'biaya_pengiriman' => $request->shipping_cost,
                'total_harga' => $carts->sum(fn($item) => $item->produk->harga_setelah_diskon * $item->jumlah),
                'total_bayar' => $carts->sum(fn($item) => $item->produk->harga_setelah_diskon * $item->jumlah) + $request->shipping_cost,
                'status' => $request->payment_method == 'cod' ? 'menunggu_pembayaran' : 'menunggu_pembayaran'
            ]);

            // 3. Create Order Items
            foreach ($carts as $cart) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $cart->produk_id,
                    'nama_produk' => $cart->produk->nama,
                    'harga' => $cart->produk->harga_setelah_diskon,
                    'jumlah' => $cart->jumlah,
                    'subtotal' => $cart->produk->harga_setelah_diskon * $cart->jumlah
                ]);
            }

            // 4. Create Payment
            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'kode_pembayaran' => 'PAY-' . time() . '-' . auth()->id(),
                'metode' => $request->payment_method,
                'jumlah' => $pesanan->total_bayar,
                'status' => 'pending',
                'expired_at' => now()->addHours(24)
            ]);

            // 5. Create Status History
            RiwayatStatusPesanan::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'menunggu_pembayaran',
                'catatan' => 'Pesanan dibuat'
            ]);

            // 6. Process Payment
            if ($request->payment_method == 'midtrans') {
                $paymentUrl = $this->generateMidtransPayment($pesanan);
                $pembayaran->update(['url_checkout' => $paymentUrl]);
                // dd($paymentUrl);
            }

            // 7. Clear Cart
            auth()->user()->carts()->whereIn('id', $cartIds)->delete();

            DB::commit();

            return $request->payment_method == 'cod'
                // ? redirect()->route('pesanan.show', $pesanan->id)->with('success', 'Pesanan COD berhasil dibuat')
                ? redirect()->back()->with('success', 'Pesanan COD berhasil dibuat')
                : redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    private function generateMidtransPayment($pesanan)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;

        $params = [
            'transaction_details' => [
                'order_id' => $pesanan->nomor_pesanan,
                'gross_amount' => $pesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->nomor_telepon,
            ],
            'item_details' => $pesanan->detailPesanan->map(fn($item) => [
                'id' => $item->produk_id,
                'price' => $item->harga,
                'quantity' => $item->jumlah,
                'name' => $item->nama_produk
            ])
        ];

        return Snap::createTransaction($params)->redirect_url;
    }
}
