<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'nama' => 'Intel',
                'deskripsi' => 'Produsen prosesor dan komponen komputer terkemuka.',
                'is_processor' => true,
                'logo' => 'intel.png',
            ],
            [
                'nama' => 'AMD',
                'deskripsi' => 'Advanced Micro Devices, produsen prosesor dan kartu grafis.',
                'is_processor' => true,
                'logo' => 'amd.png',
            ],
            [
                'nama' => 'NVIDIA',
                'deskripsi' => 'Produsen GPU dan teknologi AI terkemuka.',
                'is_processor' => false,
                'logo' => 'nvidia.png',
            ],
            [
                'nama' => 'ASUS',
                'deskripsi' => 'Produsen motherboard, laptop, dan komponen komputer.',
                'is_processor' => false,
                'logo' => 'asus.png',
            ],
            [
                'nama' => 'MSI',
                'deskripsi' => 'Micro-Star International, produsen komponen gaming dan laptop.',
                'is_processor' => false,
                'logo' => 'msi.png',
            ],
            [
                'nama' => 'Gigabyte',
                'deskripsi' => 'Produsen motherboard dan komponen komputer.',
                'is_processor' => false,
                'logo' => 'gigabyte.png',
            ],
            [
                'nama' => 'Corsair',
                'deskripsi' => 'Produsen RAM, casing, PSU, dan aksesori komputer.',
                'is_processor' => false,
                'logo' => 'corsair.png',
            ],
            [
                'nama' => 'Western Digital',
                'deskripsi' => 'Produsen perangkat penyimpanan dan solusi data.',
                'is_processor' => false,
                'logo' => 'wd.png',
            ],
            [
                'nama' => 'Samsung',
                'deskripsi' => 'Produsen SSD, RAM, dan komponen elektronik.',
                'is_processor' => false,
                'logo' => 'samsung.png',
            ],
            [
                'nama' => 'Seagate',
                'deskripsi' => 'Produsen hard drive dan solusi penyimpanan data.',
                'is_processor' => false,
                'logo' => 'seagate.png',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'nama' => $brand['nama'],
                'slug' => Str::slug($brand['nama']),
                'deskripsi' => $brand['deskripsi'],
                'is_processor' => $brand['is_processor'],
                'logo' => $brand['logo'],
            ]);
        }
    }
}
