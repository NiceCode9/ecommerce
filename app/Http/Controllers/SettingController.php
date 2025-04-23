<?php

namespace App\Http\Controllers;

use App\Http\Services\RajaOngkirService;
use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getWilayah(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $wilayah = $this->rajaOngkirService->getWilayah($keyword);

            return response()->json([
                'success' => true,
                'data' => $wilayah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateShipping(Request $request)
    {
        try {
            // $validated = $request->validate([
            //     'shipper_destination_id' => 'required|integer',
            //     'receiver_destination_id' => 'required|integer',
            //     'weight' => 'required|integer',
            //     'item_value' => 'required|integer',
            //     'cod' => 'nullable|in:yes,no'
            // ]);

            // $shipping = $this->rajaOngkirService->calculateCost(
            //     $validated['shipper_destination_id'],
            //     $validated['receiver_destination_id'],
            //     $validated['weight'],
            //     $validated['item_value'],
            //     $validated['cod'] ?? 'no'
            // );

            $shipping = $this->rajaOngkirService->calculateCost(46292, $request->receiver_destination_id, $request->weight, $request->item_value, 'yes');

            return response()->json([
                'success' => true,
                'data' => $shipping
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $pesanan = Pesanan::where('nomor_pesanan', $orderId)
            ->with(['pembayaran', 'items'])
            ->firstOrFail();

        // Update status jika belum terupdate via callback
        if ($pesanan->status == 'menunggu_pembayaran') {
            $pesanan->update(['status' => 'diproses']);
            $pesanan->pembayaran->update(['status' => 'sukses']);

            RiwayatStatusPesanan::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'diproses',
                'catatan' => 'Pembayaran berhasil diverifikasi via Midtrans'
            ]);
        }

        return view('front.payment.success', compact('pesanan'));
    }

    public function failed(Request $request)
    {
        $orderId = $request->query('order_id');
        $pesanan = Pesanan::where('nomor_pesanan', $orderId)
            ->with(['pembayaran', 'items'])
            ->firstOrFail();

        return view('front.payment.failed', compact('pesanan'));
    }
}
