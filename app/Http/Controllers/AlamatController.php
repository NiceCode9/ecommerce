<?php

namespace App\Http\Controllers;

use App\Http\Services\RajaOngkirService;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlamatController extends Controller
{

    public function getAlamat()
    {
        $alamat = auth()->user()->alamat()->get();
        return response()->json($alamat);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'api_id' => 'nullable|string',
            'label' => 'nullable|string'
        ]);

        if ($request->is_utama) {
            Alamat::where('pengguna_id', auth()->id())
                ->update(['is_utama' => false]);
        }

        Alamat::create([
            'pengguna_id' => auth()->id(),
            'nama_penerima' => $request->nama_penerima,
            'nomor_telepon' => $request->nomor_telepon,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kode_pos' => $request->kode_pos,
            'catatan' => $request->catatan,
            'is_utama' => $request->is_utama ?? 0,
            'api_id' => $request->api_id,
            'label' => $request->label,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan'
        ]);
    }
}
