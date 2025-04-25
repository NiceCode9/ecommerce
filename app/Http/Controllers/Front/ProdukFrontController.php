<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukFrontController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'brand', 'gambarUtama'])
            ->where('is_aktif', true)
            ->withCount('ulasan as ulasan_count')
            ->withAvg('ulasan', 'rating');
        dd($query->get());
        // Filter by categories
        if ($request->has('categories')) {
            $query->whereIn('kategori_id', $request->categories);
        }

        // Filter by brands
        if ($request->has('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Filter by price range
        $minPrice = $request->min_price ?? 0;
        $maxPrice = $request->max_price ?? Produk::max('harga_setelah_diskon');
        $query->whereBetween('harga_setelah_diskon', [$minPrice, $maxPrice]);

        // Sorting
        switch ($request->sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy('harga_setelah_diskon', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga_setelah_diskon', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('dilihat', 'desc'); // Popular
        }

        // Pagination
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);

        // For AJAX requests, return JSON response
        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.partials.produk-list', compact('products'))->render(),
                'showing' => $products->count(),
                'total' => $products->total()
            ]);
        }

        // Get categories and brands for filters
        $categories = Kategori::withCount(['produk' => function ($query) {
            $query->where('is_aktif', true);
        }])->get();

        $brands = Brand::withCount(['produk' => function ($query) {
            $query->where('is_aktif', true);
        }])->get();

        $maxPrice = Produk::max('harga_setelah_diskon');
        $topSelling = Produk::with('gambarUtama')
            ->where('is_aktif', true)
            ->orderBy('dilihat', 'desc')
            ->take(3)
            ->get();

        return view('front.products.store', compact('products', 'categories', 'brands', 'maxPrice', 'topSelling'));
    }

    public function show($slug)
    {
        $product = Produk::with([
            'kategori',
            'brand',
            'gambar',
            'spesifikasi',
            'motherboard',
            'kompatibel_dengan_motherboard_ini' => function ($query) {
                $query->with('gambarUtama')->where('is_aktif', true);
            },
            'ulasan' => function ($query) {
                $query->with('pengguna')->latest();
            }
        ])
            ->withAvg('ulasan', 'rating')
            ->where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        // Increment view count
        $product->increment('dilihat');

        // Get related products
        $relatedProducts = Produk::with('gambarUtama')
            ->where('kategori_id', $product->kategori_id)
            ->where('id', '!=', $product->id)
            ->where('is_aktif', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Check if user has purchased this product (for review)
        $pesananId = null;
        if (auth()->check()) {
            $pesanan = auth()->user()->pesanan()
                ->whereHas('detailPesanan', function ($query) use ($product) {
                    $query->where('produk_id', $product->id);
                })
                ->where('status', 'selesai')
                ->first();

            if ($pesanan) {
                $pesananId = $pesanan->id;
            }
        }

        return view('front.products.detail-produk', compact('product', 'relatedProducts', 'pesananId'));
    }

    public function byCategory($slug)
    {
        $category = Kategori::where('slug', $slug)->firstOrFail();

        $products = Produk::with(['gambarUtama', 'kategori'])
            ->where('kategori_id', $category->id)
            ->where('is_aktif', true)
            ->withCount('ulasan')
            ->withAvg('ulasan', 'rating')
            ->paginate(12);

        return view('front.products.store', [
            'products' => $products,
            'category' => $category,
            'categories' => Kategori::withCount('produk')->get(),
            'brands' => Brand::withCount('produk')->get(),
            'maxPrice' => Produk::max('harga_setelah_diskon')
        ]);
    }

    public function byBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = Produk::with(['gambarUtama', 'brand'])
            ->where('brand_id', $brand->id)
            ->where('is_aktif', true)
            ->withCount('ulasan')
            ->withAvg('ulasan', 'rating')
            ->paginate(12);

        return view('front.products.store', [
            'products' => $products,
            'brand' => $brand,
            'categories' => Kategori::withCount('produk')->get(),
            'brands' => Brand::withCount('produk')->get(),
            'maxPrice' => Produk::max('harga_setelah_diskon')
        ]);
    }
}
