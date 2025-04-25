<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request, $produkId)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
            'pesanan_id' => 'nullable|exists:pesanan,id'
        ]);

        // Store the review in the database
        // $review = new Ulasan();
        // $review->produk_id = $request->produk_id;
        // $review->user_id = auth()->id();
        // $review->rating = $request->rating;
        // $review->komentar = $request->komentar;
        // $review->save();

        // return response()->json(['message' => 'Review submitted successfully!'], 201);
        // Check if user has purchased this product
        if (!auth()->user()->pesanan()->whereHas('detailPesanan', function ($query) use ($produkId) {
            $query->where('produk_id', $produkId);
        })->exists()) {
            return back()->with('error', 'You must purchase this product before submitting a review.');
        }


        // Check if user has already reviewed this product
        if (Ulasan::where('produk_id', $produkId)
            ->where('pengguna_id', auth()->id())
            ->exists()
        ) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $ulasan = new Ulasan();
        $ulasan->produk_id = $produkId;
        $ulasan->pengguna_id = auth()->id();
        $ulasan->pesanan_id = $request->pesanan_id;
        $ulasan->rating = $request->rating;
        $ulasan->komentar = $request->komentar;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/ulasan');
            $ulasan->gambar = basename($path);
        }

        $ulasan->save();

        return back()->with('success', 'Thank you for your review!');
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
