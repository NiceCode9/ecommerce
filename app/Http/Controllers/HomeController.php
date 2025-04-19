<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Produk baru dengan relasi gambar dan kategori
        $products = Produk::with(['gambarUtama', 'kategori'])
            ->latest()
            ->take(10)
            ->get();

        // Produk terlaris
        $topSelling = Produk::with(['gambarUtama', 'kategori'])
            // ->orderBy('terjual', 'desc')
            ->take(10)
            ->get();

        return view('front.home', compact('products', 'topSelling'));
    }
}
