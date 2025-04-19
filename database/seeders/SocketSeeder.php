<?php

namespace Database\Seeders;

use App\Models\Socket;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class SocketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mendapatkan ID Brand
        $intel = Brand::where('nama', 'Intel')->first()->id;
        $amd = Brand::where('nama', 'AMD')->first()->id;

        // Socket Intel
        $intelSockets = [
            [
                'nama' => 'LGA 1700',
                'deskripsi' => 'Socket untuk prosesor Intel generasi ke-12 dan ke-13 (Alder Lake dan Raptor Lake)',
                'brand_id' => $intel,
            ],
            [
                'nama' => 'LGA 1200',
                'deskripsi' => 'Socket untuk prosesor Intel generasi ke-10 dan ke-11 (Comet Lake dan Rocket Lake)',
                'brand_id' => $intel,
            ],
            [
                'nama' => 'LGA 1151',
                'deskripsi' => 'Socket untuk prosesor Intel generasi ke-6, ke-7, ke-8, dan ke-9 (Skylake, Kaby Lake, Coffee Lake, dan Coffee Lake Refresh)',
                'brand_id' => $intel,
            ],
            [
                'nama' => 'LGA 2066',
                'deskripsi' => 'Socket untuk prosesor Intel HEDT (High-End Desktop) seperti Core X-series',
                'brand_id' => $intel,
            ],
        ];

        // Socket AMD
        $amdSockets = [
            [
                'nama' => 'AM5',
                'deskripsi' => 'Socket untuk prosesor AMD Ryzen generasi terbaru dengan DDR5',
                'brand_id' => $amd,
            ],
            [
                'nama' => 'AM4',
                'deskripsi' => 'Socket untuk mayoritas prosesor AMD Ryzen (Ryzen 1000 hingga 5000 series)',
                'brand_id' => $amd,
            ],
            [
                'nama' => 'TR4',
                'deskripsi' => 'Socket untuk prosesor AMD Threadripper generasi pertama dan kedua',
                'brand_id' => $amd,
            ],
            [
                'nama' => 'sTRX4',
                'deskripsi' => 'Socket untuk prosesor AMD Threadripper generasi ketiga',
                'brand_id' => $amd,
            ],
        ];

        // Gabungkan semua socket
        $allSockets = array_merge($intelSockets, $amdSockets);

        // Insert ke database
        foreach ($allSockets as $socket) {
            Socket::create($socket);
        }
    }
}
