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
        $recentOrders = Pesanan::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Data untuk grafik penjualan 30 hari terakhir
        $salesChart = $this->getSalesChartData();

        // Data untuk grafik kategori produk
        $categoryChart = $this->getCategoryChartData();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'pendingPayments',
            'recentOrders',
            'salesChart',
            'categoryChart'
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
                ->where('status', 'selesai')
                ->sum('total_bayar');

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
