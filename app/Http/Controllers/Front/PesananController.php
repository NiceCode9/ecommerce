<?php

namespace App\Http\Controllers\Front;;

use App\Http\Controllers\Controller;
use App\Models\{Pesanan, Pembayaran, RiwayatStatusPesanan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = auth()->user()->pesanan()
            ->with(['pembayaran', 'detailPesanan.produk'])
            ->withCount(['detailPesanan as total_produk' => function ($query) {
                $query->select(DB::raw('SUM(jumlah)'));
            }])
            ->latest()
            ->paginate(10);

        return view('front.pesanan.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = auth()->user()->pesanan()
            ->with([
                'alamatPengiriman',
                'detailPesanan.produk',
                'pembayaran',
                'riwayatStatus' => fn($q) => $q->latest()
            ])
            ->findOrFail($id);

        // Redirect jika COD dan belum dibayar
        if ($pesanan->pembayaran->metode == 'cod' && $pesanan->status == 'menunggu_pembayaran') {
            return view('front.pesanan.cod', compact('pesanan'));
        }

        return view('front.pesanan.show', compact('pesanan'));
    }

    public function confirmCodPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $pesanan = auth()->user()->pesanan()
                ->with('pembayaran')
                ->findOrFail($id);

            // Upload bukti pembayaran
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            // Update status
            $pesanan->update([
                'status' => 'selesai',
            ]);
            $pesanan->pembayaran->update([
                'status' => 'sukses',
                'response_data' => ['bukti_pembayaran' => $path]
            ]);

            RiwayatStatusPesanan::create([
                'pesanan_id' => $pesanan->id,
                'status' => 'menunggu_verifikasi',
                'catatan' => 'Bukti pembayaran COD diunggah'
            ]);

            DB::commit();
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('error', 'Gagal mengunggah bukti pembayaran.');
        }
    }
}
