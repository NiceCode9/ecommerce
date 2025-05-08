<?php

namespace App\Http\Controllers;

use App\Http\Services\RajaOngkirService;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AlamatController extends Controller
{

    public function getAlamat()
    {
        $alamat = auth()->user()->alamat()
            ->orderBy('is_utama', 'DESC')
            ->latest()
            ->get();

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

        $alamat = Alamat::create([
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
            'alamat' => $alamat,
            'message' => 'Alamat berhasil ditambahkan'
        ]);
    }

    public function edit($id)
    {
        $alamat = Alamat::findOr($id);
        return response()->json($alamat);
    }

    public function update(Request $request, $id)
    {
        $alamat = auth()->user()->alamat()->findOrFail($id);

        $validated = $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'catatan' => 'nullable|string',
            'is_utama' => 'boolean',
            'api_id' => 'nullable|string',
        ]);

        if ($request->is_utama) {
            auth()->user()->alamat()->where('id', '!=', $id)->update(['is_utama' => false]);
        }

        $alamat->update($validated);

        return response()->json([
            'success' => true,
            'alamat' => $alamat,
            'message' => 'Alamat berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $alamat = Alamat::where('pengguna_id', auth()->id())->findOrFail($id);
        $alamat->delete();

        // Jika alamat yang dihapus adalah alamat utama, set alamat lain sebagai utama jika ada
        if ($alamat->is_utama) {
            $alamatLain = Auth::user()->alamat()->first();
            if ($alamatLain) {
                $alamatLain->update(['is_utama' => true]);
            }
        }

        return response()->json([
            'message' => 'Alamat Berhasil dihapus'
        ]);
    }

    public function setUtama($id)
    {
        DB::beginTransaction();
        try {
            $alamat = Alamat::where('pengguna_id', auth()->id())->findOrFail($id);

            Auth::user()->alamat()->update(['is_utama' => false]);

            $alamat->update(['is_utama' => true]);

            Auth::user()->update(['alamat_utama' => $alamat->id]);
            DB::commit();
            return response()->json(['message' => 'Alamat utama berhasil diubah']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat melakukan proses ubah alamat']);
        }
    }
}
