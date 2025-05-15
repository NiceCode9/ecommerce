<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BrandImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Brand([
            'nama' => $row['nama'],
            'slug' => $row['slug'],
            'deskripsi' => $row['deskripsi'],
            'is_processor' => $row['is_processor'],
            'logo' => $row['logo'],
        ]);
    }
}
