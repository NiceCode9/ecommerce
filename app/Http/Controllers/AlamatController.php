<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlamatController extends Controller
{
    public function getProvinces()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/province',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: pHm6iubUb1bce9efb1e25e5dZF9yF4nj'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function getCities($provinceId)
    {
        try {
            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.api_key_shipping_delivery')
            ])->get('https://api.rajaongkir.com/starter/city', [
                'province' => $provinceId
            ]);

            $cities = $response->json()['rajaongkir']['results'];
            
            return response()->json([
                'success' => true,
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
            'is_utama' => 'nullable|boolean'
        ]);

        // Jika dijadikan alamat utama, reset alamat utama sebelumnya
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
            'is_utama' => $request->is_utama ?? false,
            'city_id' => explode('|', $request->kota)[0] // Simpan ID kota untuk RajaOngkir
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan'
        ]);
    }
}
