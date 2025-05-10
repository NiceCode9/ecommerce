<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:summary,detailed,product,category',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        switch ($request->report_type) {
            case 'summary':
                return $this->generateSummaryReport($startDate, $endDate);
            case 'detailed':
                return $this->generateDetailedReport($startDate, $endDate);
            case 'product':
                return $this->generateProductReport($startDate, $endDate);
            case 'category':
                return $this->generateCategoryReport($startDate, $endDate);
        }
    }

    protected function generateSummaryReport($startDate, $endDate)
    {
        $orders = Pesanan::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->get();

        $totalRevenue = $orders->sum('total_bayar');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $data = [
            'start_date' => $startDate->format('d F Y'),
            'end_date' => $endDate->format('d F Y'),
            'report_type' => 'Ringkasan Penjualan',
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'orders_by_status' => $this->getOrdersByStatus($startDate, $endDate),
            'daily_sales' => $this->getDailySalesData($startDate, $endDate),
        ];

        if (request()->has('export') && request()->export == 'pdf') {
            $pdf = PDF::loadView('admin.reports.templates.summary', $data);
            return $pdf->download('laporan-ringkasan-penjualan-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
        }

        return view('admin.reports.summary', $data);
    }

    protected function generateDetailedReport($startDate, $endDate)
    {
        $orders = Pesanan::with(['pengguna', 'detailPesanan.produk'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'start_date' => $startDate->format('d F Y'),
            'end_date' => $endDate->format('d F Y'),
            'report_type' => 'Detail Penjualan',
            'orders' => $orders,
            'total_revenue' => $orders->where('status', 'selesai')->sum('total_bayar'),
        ];

        if (request()->has('export') && request()->export == 'pdf') {
            $pdf = PDF::loadView('admin.reports.templates.detailed', $data);
            return $pdf->download('laporan-detail-penjualan-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
        }

        return view('admin.reports.detailed', $data);
    }

    protected function generateProductReport($startDate, $endDate)
    {
        $products = Produk::with(['kategori'])
            ->withCount(['detailPesanan as total_terjual' => function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(jumlah), 0)'))
                    ->whereHas('pesanan', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'selesai');
                    });
            }])
            ->withSum(['detailPesanan as total_pendapatan' => function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(subtotal), 0)'))
                    ->whereHas('pesanan', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'selesai');
                    });
            }])
            ->orderByDesc('total_terjual')
            ->get();

        $data = [
            'start_date' => $startDate->format('d F Y'),
            'end_date' => $endDate->format('d F Y'),
            'report_type' => 'Laporan Produk',
            'products' => $products,
            'total_revenue' => $products->sum('total_pendapatan'),
        ];

        if (request()->has('export') && request()->export == 'pdf') {
            $pdf = PDF::loadView('admin.reports.templates.product', $data);
            return $pdf->download('laporan-produk-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
        }

        return view('admin.reports.product', $data);
    }

    protected function generateCategoryReport($startDate, $endDate)
    {
        $categories = Kategori::with(['produk'])
            ->withCount(['produk as total_terjual' => function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(detail_pesanan.jumlah), 0)'))
                    ->join('detail_pesanan', 'detail_pesanan.produk_id', '=', 'produk.id')
                    ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                    ->whereBetween('pesanan.created_at', [$startDate, $endDate])
                    ->where('pesanan.status', 'selesai');
            }])
            ->withSum(['produk as total_pendapatan' => function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(detail_pesanan.subtotal), 0)'))
                    ->join('detail_pesanan', 'detail_pesanan.produk_id', '=', 'produk.id')
                    ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                    ->whereBetween('pesanan.created_at', [$startDate, $endDate])
                    ->where('pesanan.status', 'selesai');
            }])
            ->orderByDesc('total_pendapatan')
            ->get();

        $data = [
            'start_date' => $startDate->format('d F Y'),
            'end_date' => $endDate->format('d F Y'),
            'report_type' => 'Laporan Kategori',
            'categories' => $categories,
            'total_revenue' => $categories->sum('total_pendapatan'),
        ];

        if (request()->has('export') && request()->export == 'pdf') {
            $pdf = Pdf::loadView('admin.reports.templates.category', $data);
            return $pdf->download('laporan-kategori-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
        }

        return view('admin.reports.category', $data);
    }

    protected function getOrdersByStatus($startDate, $endDate)
    {
        return Pesanan::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->total];
            });
    }

    protected function getDailySalesData($startDate, $endDate)
    {
        $salesData = [];
        $dateRange = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $dateRange[] = $date->format('d M');

            $total = Pesanan::whereDate('created_at', $formattedDate)
                ->where('status', 'selesai')
                ->sum('total_bayar');

            $salesData[] = $total ?? 0;
        }

        return [
            'dates' => $dateRange,
            'sales' => $salesData
        ];
    }
}
