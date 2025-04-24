<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagePesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['pengguna', 'pembayaran'])
            ->latest()
            ->paginate(10);

        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with([
            'pengguna',
            'alamatPengiriman',
            'detailPesanan.produk',
            'pembayaran',
            'riwayatStatus' => fn($q) => $q->latest()
        ])->findOrFail($id);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai,dibatalkan',
            'catatan' => 'nullable|string',
            'no_resi' => 'required_if:status,dikirim'
        ]);

        $pesanan = Pesanan::findOrFail($id);

        DB::transaction(function () use ($request, $pesanan) {
            $pesanan->update([
                'status' => $request->status,
                'nomor_resi' => $request->no_resi ?? null
            ]);

            RiwayatStatusPesanan::create([
                'pesanan_id' => $pesanan->id,
                'status' => $request->status,
                'catatan' => $request->catatan ?? 'Status diperbarui'
            ]);
        });

        return back()->with('success', 'Status pesanan diperbarui');
    }
}
