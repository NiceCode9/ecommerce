<?php

namespace App\Http\Controllers\Front;;

use App\Http\Controllers\Controller;
use App\Http\Services\RajaOngkirService;
use App\Models\Alamat;
use App\Models\BuildComponent;
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
        $cartIds = request('cart_ids', []);
        $mode = request('mode', 'normal');

        // Validasi cart_ids
        if (empty($cartIds)) {
            return redirect()->back()->with('error', 'Silakan pilih produk untuk checkout');
        }

        $user = auth()->user();

        if ($mode == "normal") {
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
        } else {
            $builds = $user->builds()
                ->with('components.produk')
                ->find(request('build_id'));

            $cartItems = $builds->components()
                ->whereIn('id', $cartIds)
                ->with('produk')
                ->get();

            // Hitung subtotal dan total berat
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->produk->harga_setelah_diskon;
            });

            $totalWeight = $cartItems->sum(function ($item) {
                return $item->quantity * $item->produk->berat;
            });

            $totalItems = $cartItems->sum('quantity');
        }


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
            'cart_ids' => 'required',
            'alamat_id' => 'required|exists:alamat,id,pengguna_id,' . auth()->id(),
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,midtrans'
        ]);

        DB::beginTransaction();
        try {
            $cartIds = json_decode($request->cart_ids, true);
            $carts = auth()->user()->carts()
                ->whereIn('id', $cartIds)
                ->with('produk')
                ->get();

            if ($carts->isEmpty()) {
                $carts = BuildComponent::whereIn('id', $cartIds)
                    ->with('produk')
                    ->get();
            }

            $totalProduk = $carts->sum(fn($item) => $item->produk->harga_setelah_diskon * ($item->jumlah ?? $item->quantity));
            $totalBayar = $totalProduk + $request->shipping_cost;

            $pesanan = Pesanan::create([
                'nomor_pesanan' => 'INV-' . time() . '-' . auth()->id(),
                'pengguna_id' => auth()->id(),
                'alamat_pengiriman_id' => $request->alamat_id,
                'metode_pengiriman' => $request->shipping_name, // Default, bisa disesuaikan
                'biaya_pengiriman' => $request->shipping_cost,
                'total_harga' => $totalProduk,
                'total_bayar' => $totalBayar,
                'status' => $request->payment_method == 'cod' ? 'menunggu_pembayaran' : 'menunggu_pembayaran'
            ]);

            // 3. Create Order Items
            foreach ($carts as $cart) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $cart->produk_id,
                    'nama_produk' => $cart->produk->nama,
                    'harga' => $cart->produk->harga_setelah_diskon,
                    'jumlah' => $cart->jumlah ?? $cart->quantity,
                    'subtotal' => $cart->produk->harga_setelah_diskon * ($cart->jumlah ?? $item->quantity)
                ]);
            }

            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'kode_pembayaran' => 'PAY-' . time() . '-' . auth()->id(),
                'metode' => $request->payment_method,
                'jumlah' => $pesanan->total_bayar,
                'status' => 'pending',
                'expired_at' => now()->addHours(24)
            ]);


            // 6. Process Payment
            if ($request->payment_method == 'midtrans') {
                $paymentUrl = $this->generateMidtransPayment($pesanan, $request->shipping_cost);
                $pembayaran->update(['url_checkout' => $paymentUrl]);
                RiwayatStatusPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'status' => 'menunggu_pembayaran',
                    'catatan' => 'Pesanan dibuat'
                ]);
            } else {
                RiwayatStatusPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'status' => 'diproses',
                    'catatan' => 'Pesanan dibuat'
                ]);
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

    private function generateMidtransPayment($pesanan, $shippingCost)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $itemDetails = $pesanan->detailPesanan->map(function ($item) {
            // Potong nama produk jika lebih dari 50 karakter
            $itemName = strlen($item->nama_produk) > 50
                ? substr($item->nama_produk, 0, 47) . '...'
                : $item->nama_produk;

            return [
                'id' => $item->produk_id,
                'price' => (int) $item->harga,
                'quantity' => (int) $item->jumlah,
                'name' => $itemName
            ];
        })->toArray();

        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING-' . $pesanan->id,
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . $pesanan->metode_pengiriman . ')'
            ];
        }

        $grossAmount = array_reduce($itemDetails, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        $params = [
            'transaction_details' => [
                'order_id' => $pesanan->nomor_pesanan,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->nomor_telepon,
            ],
            // 'item_details' => $pesanan->detailPesanan->map(fn($item) => [
            //     'id' => $item->produk_id,
            //     'price' => $item->harga,
            //     'quantity' => $item->jumlah,
            //     'name' => $item->nama_produk
            // ])
            'item_details' => $itemDetails,
            'expiry' => [
                'unit' => 'hour',
                'duration' => 24
            ]
        ];

        try {
            // $snapToken = Snap::getSnapToken($params);
            // return Snap::getSnapUrl($snapToken);
            return Snap::createTransaction($params)->redirect_url;
        } catch (\Exception $e) {
            throw new \Exception("Gagal membuat pembayaran Midtrans: " . $e->getMessage());
        }
    }
}
