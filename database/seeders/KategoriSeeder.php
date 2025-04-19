<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kategori utama
        $mainCategories = [
            [
                'nama' => 'Komponen Komputer',
                'deskripsi' => 'Berbagai komponen utama untuk membangun PC',
                'gambar' => 'komponen.jpg',
                'tipe' => 'general',
                'subcategories' => [
                    [
                        'nama' => 'Processor',
                        'deskripsi' => 'CPU untuk komputer desktop dan server',
                        'gambar' => 'processor.jpg',
                        'tipe' => 'processor',
                    ],
                    [
                        'nama' => 'Motherboard',
                        'deskripsi' => 'Papan induk untuk menghubungkan semua komponen komputer',
                        'gambar' => 'motherboard.jpg',
                        'tipe' => 'motherboard',
                    ],
                    [
                        'nama' => 'RAM',
                        'deskripsi' => 'Memori akses acak untuk komputer',
                        'gambar' => 'ram.jpg',
                        'tipe' => 'memory',
                    ],
                    [
                        'nama' => 'VGA Card',
                        'deskripsi' => 'Kartu grafis untuk mengolah tampilan visual',
                        'gambar' => 'vga.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Storage',
                        'deskripsi' => 'Media penyimpanan data komputer',
                        'gambar' => 'storage.jpg',
                        'tipe' => 'general',
                        'subcategories' => [
                            [
                                'nama' => 'SSD',
                                'deskripsi' => 'Solid State Drive untuk kecepatan akses data',
                                'gambar' => 'ssd.jpg',
                                'tipe' => 'general',
                            ],
                            [
                                'nama' => 'Hard Disk',
                                'deskripsi' => 'Hard Disk Drive untuk penyimpanan massal',
                                'gambar' => 'hdd.jpg',
                                'tipe' => 'general',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'nama' => 'Periferal',
                'deskripsi' => 'Perangkat pendukung komputer',
                'gambar' => 'periferal.jpg',
                'tipe' => 'general',
                'subcategories' => [
                    [
                        'nama' => 'Keyboard',
                        'deskripsi' => 'Papan ketik untuk input data',
                        'gambar' => 'keyboard.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Mouse',
                        'deskripsi' => 'Perangkat penunjuk untuk navigasi',
                        'gambar' => 'mouse.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Monitor',
                        'deskripsi' => 'Layar tampilan komputer',
                        'gambar' => 'monitor.jpg',
                        'tipe' => 'general',
                    ],
                ]
            ],
            [
                'nama' => 'Networking',
                'deskripsi' => 'Perangkat jaringan komputer',
                'gambar' => 'networking.jpg',
                'tipe' => 'general',
                'subcategories' => [
                    [
                        'nama' => 'Router',
                        'deskripsi' => 'Perangkat untuk menghubungkan jaringan',
                        'gambar' => 'router.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Switch',
                        'deskripsi' => 'Perangkat untuk menghubungkan komputer dalam jaringan',
                        'gambar' => 'switch.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Network Card',
                        'deskripsi' => 'Kartu untuk konektivitas jaringan',
                        'gambar' => 'network-card.jpg',
                        'tipe' => 'general',
                    ],
                ]
            ],
            [
                'nama' => 'Pendingin',
                'deskripsi' => 'Sistem pendingin untuk komputer',
                'gambar' => 'cooling.jpg',
                'tipe' => 'general',
                'subcategories' => [
                    [
                        'nama' => 'CPU Cooler',
                        'deskripsi' => 'Pendingin khusus untuk prosesor',
                        'gambar' => 'cpu-cooler.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Case Fan',
                        'deskripsi' => 'Kipas untuk sirkulasi udara dalam casing',
                        'gambar' => 'case-fan.jpg',
                        'tipe' => 'general',
                    ],
                    [
                        'nama' => 'Liquid Cooling',
                        'deskripsi' => 'Sistem pendingin berbasis cairan',
                        'gambar' => 'liquid-cooling.jpg',
                        'tipe' => 'general',
                    ],
                ]
            ],
            [
                'nama' => 'Power Supply',
                'deskripsi' => 'Catu daya untuk komputer',
                'gambar' => 'psu.jpg',
                'tipe' => 'general',
            ],
        ];

        // Fungsi rekursif untuk membuat kategori dan subkategori
        $createCategories = function ($categories, $parentId = null) use (&$createCategories) {
            foreach ($categories as $category) {
                $newCategory = Kategori::create([
                    'nama' => $category['nama'],
                    'slug' => Str::slug($category['nama']),
                    'deskripsi' => $category['deskripsi'],
                    'gambar' => $category['gambar'],
                    'parent_id' => $parentId,
                    'tipe' => $category['tipe'],
                ]);

                if (isset($category['subcategories'])) {
                    $createCategories($category['subcategories'], $newCategory->id);
                }
            }
        };

        $createCategories($mainCategories);
    }
}
