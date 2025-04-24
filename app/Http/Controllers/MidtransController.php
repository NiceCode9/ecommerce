<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    // MidtransController.php
    public function handleNotification(Request $request)
    {
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::where('nomor_pesanan', $orderId)->firstOrFail();
            $pembayaran = $pesanan->pembayaran;

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $pembayaran->update(['status' => 'pending']);
                } else if ($fraud == 'accept') {
                    $pembayaran->update(['status' => 'sukses', 'waktu_dibayar' => now()]);
                    $pesanan->update(['status' => 'diproses']);
                    RiwayatStatusPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'status' => 'diproses',
                        'catatan' => 'Pembayaran berhasil diverifikasi'
                    ]);
                }
            } else if ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
                $pembayaran->update(['status' => 'gagal']);
                $pesanan->update(['status' => 'dibatalkan']);
                RiwayatStatusPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'status' => 'dibatalkan',
                    'catatan' => 'Pembayaran ' . $transaction
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
