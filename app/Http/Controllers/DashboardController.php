<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalOrders = Pesanan::count();
        $totalRevenue = Pesanan::where('status', 'selesai')->sum('total_bayar');
        $totalProducts = Produk::count();
        $pendingPayments = Pembayaran::where('status', 'pending')->count();

        // Pesanan terbaru
        $recentOrders = Pesanan::with('pengguna')
            ->latest()
            ->take(10)
            ->get();

        // Data untuk grafik penjualan 30 hari terakhir
        $salesChart = $this->getSalesChartData();

        // Data untuk grafik kategori produk
        $categoryChart = $this->getCategoryChartData();

        // Produk terlaris (berdasarkan jumlah penjualan)
        $bestSellingProducts = Produk::withCount(['detailPesanan as total_terjual' => function ($query) {
            $query->select(DB::raw('COALESCE(SUM(jumlah), 0)'));
        }])
            ->orderByDesc('total_terjual')
            ->take(10)
            ->get();

        // Produk paling sering dilihat
        $mostViewedProducts = Produk::orderByDesc('dilihat')
            ->take(10)
            ->get();

        // Produk dengan rating tertinggi
        $topRatedProducts = Produk::where('rating', '>', 0)
            ->orderByDesc('rating')
            ->take(10)
            ->get();

        // Produk dengan rating terendah (hanya yang memiliki rating)
        $lowRatedProducts = Produk::where('rating', '>', 0)
            ->orderBy('rating')
            ->take(10)
            ->get();

        // Produk yang hampir habis stoknya (stok < 10)
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->orderBy('stok')
            ->take(10)
            ->get();

        // Produk yang habis stok
        $outOfStockProducts = Produk::where('stok', '<=', 0)
            ->take(10)
            ->get();

        return view('admin.dashboard.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'pendingPayments',
            'recentOrders',
            'salesChart',
            'categoryChart',
            'bestSellingProducts',
            'mostViewedProducts',
            'topRatedProducts',
            'lowRatedProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    protected function getSalesChartData()
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        $dates = [];
        $salesData = [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $dates[] = $date->format('d M');

            $total = Pembayaran::whereDate('created_at', $formattedDate)
                ->where('status', 'sukses')
                ->sum('jumlah');

            $salesData[] = $total ?? 0;
        }

        return [
            'labels' => $dates,
            'data' => $salesData
        ];
    }

    protected function getCategoryChartData()
    {
        $categories = Kategori::withCount(['produk' => function ($query) {
            $query->where('is_aktif', true);
        }])
            ->orderBy('produk_count', 'desc')
            ->limit(8)
            ->get();

        $labels = $categories->pluck('nama');
        $data = $categories->pluck('produk_count');

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
