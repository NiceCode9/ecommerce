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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', '30days'); // default 30 hari terakhir
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        switch ($filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
                $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
                break;
            default: // 30days
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
                break;
        }

        // Statistik utama dengan filter tanggal
        $totalOrders = Pesanan::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Pesanan::where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_bayar');
        $totalProducts = Produk::count(); // Ini tidak perlu filter tanggal
        $pendingPayments = Pembayaran::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Pesanan terbaru dengan filter tanggal
        $recentOrders = Pesanan::with('pengguna')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        // Data untuk grafik penjualan dengan filter
        $salesChart = $this->getSalesChartData($startDate, $endDate);

        // Data untuk grafik kategori produk (tidak perlu filter tanggal)
        $categoryChart = $this->getCategoryChartData();

        // Produk terlaris dengan filter tanggal
        $bestSellingProducts = Produk::withCount(['detailPesanan as total_terjual' => function ($query) use ($startDate, $endDate) {
            $query->select(DB::raw('COALESCE(SUM(jumlah), 0)'))
                ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->whereBetween('pesanan.created_at', [$startDate, $endDate]);
        }])
            ->orderByDesc('total_terjual')
            ->take(10)
            ->get();

        // Produk paling sering dilihat (tidak perlu filter tanggal)
        $mostViewedProducts = Produk::orderByDesc('dilihat')
            ->take(10)
            ->get();

        // Produk dengan rating tertinggi (tidak perlu filter tanggal)
        $topRatedProducts = Produk::where('rating', '>', 0)
            ->orderByDesc('rating')
            ->take(10)
            ->get();

        // Produk dengan rating terendah (tidak perlu filter tanggal)
        $lowRatedProducts = Produk::where('rating', '>', 0)
            ->orderBy('rating')
            ->take(10)
            ->get();

        // Produk yang hampir habis stok (tidak perlu filter tanggal)
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->orderBy('stok')
            ->take(10)
            ->get();

        // Produk yang habis stok (tidak perlu filter tanggal)
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
            'outOfStockProducts',
            'filter',
            'startDate',
            'endDate'
        ));
    }

    protected function getSalesChartData($startDate, $endDate)
    {
        $dates = [];
        $salesData = [];
        $diffInDays = $startDate->diffInDays($endDate);

        // Format label berdasarkan rentang waktu
        if ($diffInDays <= 1) {
            // Harian (per jam)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addHour()) {
                $formattedDate = $date->format('Y-m-d H:00:00');
                $dates[] = $date->format('H:i');

                $total = Pembayaran::whereBetween('created_at', [
                    $formattedDate,
                    $date->copy()->addHour()->format('Y-m-d H:00:00')
                ])
                    ->where('status', 'sukses')
                    ->sum('jumlah');

                $salesData[] = $total ?? 0;
            }
        } elseif ($diffInDays <= 31) {
            // Mingguan/Bulanan (per hari)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');
                $dates[] = $date->format('d M');

                $total = Pembayaran::whereDate('created_at', $formattedDate)
                    ->where('status', 'sukses')
                    ->sum('jumlah');

                $salesData[] = $total ?? 0;
            }
        } elseif ($diffInDays <= 365) {
            // Tahunan (per bulan)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
                $formattedDate = $date->format('Y-m');
                $dates[] = $date->format('M Y');

                $total = Pembayaran::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'sukses')
                    ->sum('jumlah');

                $salesData[] = $total ?? 0;
            }
        } else {
            // Lebih dari 1 tahun (per tahun)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addYear()) {
                $formattedDate = $date->format('Y');
                $dates[] = $date->format('Y');

                $total = Pembayaran::whereYear('created_at', $date->year)
                    ->where('status', 'sukses')
                    ->sum('jumlah');

                $salesData[] = $total ?? 0;
            }
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
