<?php

namespace App\Http\Controllers\Front;;

use App\Http\Controllers\Controller;
use App\Models\{Pesanan, Pembayaran, RiwayatStatusPesanan};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function actionConfirm(string $id, Request $request)
    {
        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->update([
                'status' => $request->actionConfirm,
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'catatan' => $request->catatan_pembatalan,
            ]);

            RiwayatStatusPesanan::create([
                'pesanan_id' => $id,
                'status' => $request->actionConfirm,
                'catatan' => $request->alasan_pembatalan ?? '',
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Proses ' . Str::upper($request->actionConfirm) . ' Berhasil');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Proses ' . Str::upper($request->actionConfirm) . ' Gagal');
        }
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

    // public function review($id)
    // {
    //     $pesanan = Pesanan::with(['detailPesanan.produk'])->findOrFail($id);

    //     return view('front.pesanan.review', [
    //         'pesanan' => $pesanan,
    //         'produks' => $pesanan->detailPesanan
    //     ]);
    // }

    public function reviewPage($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk.gambarUtama'])
            ->where('pengguna_id', auth()->id())
            ->where('status', 'selesai')
            ->findOrFail($id);

        // Filter hanya produk yang belum diulas
        $produks = $pesanan->detailPesanan->filter(function ($item) use ($pesanan) {
            return !$item->produk->hasBeenReviewed(auth()->id(), $pesanan->id);
        });

        if ($produks->isEmpty()) {
            return redirect()->back()
                ->with('info', 'Anda sudah memberikan ulasan untuk semua produk dalam pesanan ini.');
        }

        return view('front.pesanan.review', [
            'pesanan' => $pesanan,
            'produks' => $produks
        ]);
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
