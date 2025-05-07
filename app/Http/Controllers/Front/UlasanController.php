<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    // public function store(Request $request, $produkId)
    // {
    //     // $request->validate([
    //     //     'produk_id' => 'required|exists:produks,id',
    //     //     'rating' => 'required|integer|min:1|max:5',
    //     //     'komentar' => 'nullable|string|max:1000',
    //     //     'pesanan_id' => 'nullable|exists:pesanan,id'
    //     // ]);

    //     // Store the review in the databases
    //     // $review = new Ulasan();
    //     // $review->produk_id = $request->produk_id;
    //     // $review->user_id = auth()->id();
    //     // $review->rating = $request->rating;
    //     // $review->komentar = $request->komentar;
    //     // $review->save();

    //     // return response()->json(['message' => 'Review submitted successfully!'], 201);
    //     // Check if user has purchased this product
    //     if (!auth()->user()->pesanan()->whereHas('detailPesanan', function ($query) use ($produkId) {
    //         $query->where('produk_id', $produkId);
    //     })->exists() || isset($produkId)) {
    //         return back()->with('error', 'You must purchase this product before submitting a review.');
    //     }


    //     // Check if user has already reviewed this product
    //     if (Ulasan::where('produk_id', $produkId)
    //         ->where('pengguna_id', auth()->id())
    //         ->exists()
    //     ) {
    //         return back()->with('error', 'You have already reviewed this product.');
    //     }

    //     $ulasan = new Ulasan();
    //     $ulasan->produk_id = $produkId;
    //     $ulasan->pengguna_id = auth()->id();
    //     $ulasan->pesanan_id = $request->pesanan_id;
    //     $ulasan->rating = $request->rating;
    //     $ulasan->komentar = $request->komentar;

    //     if ($request->hasFile('gambar')) {
    //         $path = $request->file('gambar')->store('public/ulasan');
    //         $ulasan->gambar = basename($path);
    //     }

    //     $ulasan->save();

    //     return back()->with('success', 'Thank you for your review!');
    // }

    public function store(Request $request, $produkId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
            'pesanan_id' => 'required|exists:pesanan,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Cek apakah user memiliki pesanan ini
        $pesanan = auth()->user()->pesanan()
            ->where('id', $request->pesanan_id)
            ->where('status', 'selesai')
            ->first();

        if (!$pesanan) {
            return back()->with('error', 'Anda tidak memiliki pesanan yang valid untuk produk ini.');
        }

        // Cek apakah produk ada dalam pesanan
        if (!$pesanan->detailPesanan()->where('produk_id', $produkId)->exists()) {
            return back()->with('error', 'Produk ini tidak ada dalam pesanan Anda.');
        }

        // Cek apakah sudah pernah memberi ulasan
        if (Ulasan::where('produk_id', $produkId)
            ->where('pengguna_id', auth()->id())
            ->where('pesanan_id', $request->pesanan_id)
            ->exists()
        ) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini dari pesanan ini.');
        }

        DB::beginTransaction();
        try {
            $ulasan = new Ulasan();
            $ulasan->produk_id = $produkId;
            $ulasan->pengguna_id = auth()->id();
            $ulasan->pesanan_id = $request->pesanan_id;
            $ulasan->rating = $request->rating;
            $ulasan->komentar = $request->komentar;

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('public/ulasan');
                $ulasan->gambar = str_replace('public/', '', $path);
            }

            $ulasan->save();

            // Update rating produk
            $product = Produk::find($produkId);
            $product->rating = Ulasan::where('produk_id', $produkId)->avg('rating');
            $product->save();

            // Jika dari halaman review multi produk, redirect kembali ke halaman tersebut
            if (strpos(url()->previous(), 'pesanan/review')) {
                return redirect()->route('pelanggan.pesanan.review', $request->pesanan_id)
                    ->with('success', 'Ulasan berhasil disimpan!');
            }

            DB::commit();
            return back()->with('success', 'Terima kasih atas ulasan Anda!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan ulasan: ' . $e->getMessage());
        }
    }

    public function reply(Request $request, $ulasanId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'balasan_admin' => 'required|string|max:1000'
        ]);

        $ulasan = Ulasan::findOrFail($ulasanId);
        $ulasan->balasan_admin = $request->balasan_admin;
        $ulasan->save();

        return back()->with('success', 'Reply submitted successfully.');
    }
}
