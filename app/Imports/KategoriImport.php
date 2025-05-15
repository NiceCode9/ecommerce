<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Kategori([
            'nama' => $row['nama'],
            'slug' => $row['slug'],
            'deskripsi' => $row['deskripsi'],
            'gambar' => $row['gambar'],
            'parent_id' => $row['parent_id'],
            'tipe' => $row['tipe'],
        ]);
    }
}
