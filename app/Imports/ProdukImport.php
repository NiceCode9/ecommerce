<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Produk([
            'nama' => $row['nama'],
            'slug' => $row['slug'],
            'sku' => $row['sku'],
            'deskripsi' => $row['deskripsi'],
            'harga' => $row['harga'],
            'harga_setelah_diskon' => $row['harga_setelah_diskon'],
            'diskon' => $row['diskon'],
            'stok' => $row['stok'],
            'berat' => $row['berat'],
            'kondisi' => $row['kondisi'],
            'kategori_id' => $row['kategori_id'],
            'brand_id' => $row['brand_id'],
            'socket_id' => $row['socket_id'],
            'mobo_id' => null,
            'garansi_bulan' => $row['garansi_bulan'],
            'rating' => $row['rating'],
            'is_aktif' => $row['is_aktif'],
            'dilihat' => $row['dilihat'] ?? 0,
        ]);
    }
}
