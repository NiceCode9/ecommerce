<?php

namespace App\Imports;

use App\Models\Socket;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SocketImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Socket([
            'nama' => $row['nama'],
            'deskripsi' => $row['deskripsi'],
            'brand_id' => $row['brand_id'],
        ]);
    }
}
