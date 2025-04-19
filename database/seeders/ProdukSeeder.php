<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Socket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mendapatkan ID dari kategori
        $processorKategori = Kategori::where('nama', 'Processor')->first()->id;
        $motherboardKategori = Kategori::where('nama', 'Motherboard')->first()->id;
        $ramKategori = Kategori::where('nama', 'RAM')->first()->id;
        $vgaKategori = Kategori::where('nama', 'VGA Card')->first()->id;
        $ssdKategori = Kategori::where('nama', 'SSD')->first()->id;
        $hddKategori = Kategori::where('nama', 'Hard Disk')->first()->id;

        // Mendapatkan ID dari brands
        $intelId = Brand::where('nama', 'Intel')->first()->id;
        $amdId = Brand::where('nama', 'AMD')->first()->id;
        $nvidiaId = Brand::where('nama', 'NVIDIA')->first()->id;
        $asusId = Brand::where('nama', 'ASUS')->first()->id;
        $msiId = Brand::where('nama', 'MSI')->first()->id;
        $gigabyteId = Brand::where('nama', 'Gigabyte')->first()->id;
        $corsairId = Brand::where('nama', 'Corsair')->first()->id;
        $wdId = Brand::where('nama', 'Western Digital')->first()->id;
        $samsungId = Brand::where('nama', 'Samsung')->first()->id;
        $seagateId = Brand::where('nama', 'Seagate')->first()->id;

        // Mendapatkan ID dari sockets
        $lga1700Id = Socket::where('nama', 'LGA 1700')->first()->id;
        $lga1200Id = Socket::where('nama', 'LGA 1200')->first()->id;
        $am5Id = Socket::where('nama', 'AM5')->first()->id;
        $am4Id = Socket::where('nama', 'AM4')->first()->id;

        // Data untuk processor
        $processors = [
            [
                'nama' => 'Intel Core i9-13900K',
                'sku' => 'PROC-I9-13900K',
                'deskripsi' => 'Processor Intel Core i9 generasi ke-13 dengan 24 core dan 32 thread. Clock speed hingga 5.8GHz.',
                'harga' => 8500000,
                'diskon' => 5,
                'stok' => 15,
                'berat' => 0.3,
                'kondisi' => 'Baru',
                'kategori_id' => $processorKategori,
                'brand_id' => $intelId,
                'socket_id' => $lga1700Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 500,
            ],
            [
                'nama' => 'Intel Core i7-13700K',
                'sku' => 'PROC-I7-13700K',
                'deskripsi' => 'Processor Intel Core i7 generasi ke-13 dengan 16 core dan 24 thread. Clock speed hingga 5.4GHz.',
                'harga' => 6200000,
                'diskon' => 0,
                'stok' => 25,
                'berat' => 0.3,
                'kondisi' => 'Baru',
                'kategori_id' => $processorKategori,
                'brand_id' => $intelId,
                'socket_id' => $lga1700Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 450,
            ],
            [
                'nama' => 'Intel Core i5-12600K',
                'sku' => 'PROC-I5-12600K',
                'deskripsi' => 'Processor Intel Core i5 generasi ke-12 dengan 10 core dan 16 thread. Clock speed hingga 4.9GHz.',
                'harga' => 4500000,
                'diskon' => 10,
                'stok' => 30,
                'berat' => 0.3,
                'kondisi' => 'Baru',
                'kategori_id' => $processorKategori,
                'brand_id' => $intelId,
                'socket_id' => $lga1700Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 400,
            ],
            [
                'nama' => 'AMD Ryzen 9 7950X',
                'sku' => 'PROC-R9-7950X',
                'deskripsi' => 'Processor AMD Ryzen 9 generasi ke-7 dengan 16 core dan 32 thread. Clock speed hingga 5.7GHz.',
                'harga' => 9800000,
                'diskon' => 3,
                'stok' => 12,
                'berat' => 0.3,
                'kondisi' => 'Baru',
                'kategori_id' => $processorKategori,
                'brand_id' => $amdId,
                'socket_id' => $am5Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 480,
            ],
            [
                'nama' => 'AMD Ryzen 7 7700X',
                'sku' => 'PROC-R7-7700X',
                'deskripsi' => 'Processor AMD Ryzen 7 generasi ke-7 dengan 8 core dan 16 thread. Clock speed hingga 5.4GHz.',
                'harga' => 5600000,
                'diskon' => 0,
                'stok' => 20,
                'berat' => 0.3,
                'kondisi' => 'Baru',
                'kategori_id' => $processorKategori,
                'brand_id' => $amdId,
                'socket_id' => $am5Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 420,
            ],
        ];

        // Data untuk motherboard
        $motherboards = [
            [
                'nama' => 'ASUS ROG Maximus Z790 Hero',
                'sku' => 'MB-ASUS-Z790-HERO',
                'deskripsi' => 'Motherboard premium untuk processor Intel generasi ke-12 dan ke-13 dengan fitur gaming dan overclocking.',
                'harga' => 9300000,
                'diskon' => 0,
                'stok' => 10,
                'berat' => 2.5,
                'kondisi' => 'Baru',
                'kategori_id' => $motherboardKategori,
                'brand_id' => $asusId,
                'socket_id' => $lga1700Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 300,
            ],
            [
                'nama' => 'MSI MAG B760 TOMAHAWK WIFI',
                'sku' => 'MB-MSI-B760-TOMAHAWK',
                'deskripsi' => 'Motherboard mid-range untuk processor Intel generasi ke-12 dan ke-13 dengan fitur WiFi 6E.',
                'harga' => 3800000,
                'diskon' => 5,
                'stok' => 15,
                'berat' => 2.2,
                'kondisi' => 'Baru',
                'kategori_id' => $motherboardKategori,
                'brand_id' => $msiId,
                'socket_id' => $lga1700Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 250,
            ],
            [
                'nama' => 'ASUS ROG STRIX X670E-E GAMING WIFI',
                'sku' => 'MB-ASUS-X670E-E',
                'deskripsi' => 'Motherboard high-end untuk processor AMD Ryzen generasi ke-7 dengan fitur DDR5 dan PCIe 5.0.',
                'harga' => 8200000,
                'diskon' => 0,
                'stok' => 8,
                'berat' => 2.4,
                'kondisi' => 'Baru',
                'kategori_id' => $motherboardKategori,
                'brand_id' => $asusId,
                'socket_id' => $am5Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 280,
            ],
            [
                'nama' => 'Gigabyte B650 AORUS ELITE AX',
                'sku' => 'MB-GIGABYTE-B650-AORUS',
                'deskripsi' => 'Motherboard mid-range untuk processor AMD Ryzen generasi ke-7 dengan fitur WiFi 6E dan audio premium.',
                'harga' => 4300000,
                'diskon' => 5,
                'stok' => 12,
                'berat' => 2.3,
                'kondisi' => 'Baru',
                'kategori_id' => $motherboardKategori,
                'brand_id' => $gigabyteId,
                'socket_id' => $am5Id,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 220,
            ],
        ];

        // Menyimpan processor dan motherboard terlebih dahulu untuk mendapatkan ID-nya
        foreach ($processors as $processor) {
            Produk::create([
                'nama' => $processor['nama'],
                'slug' => Str::slug($processor['nama']),
                'sku' => $processor['sku'],
                'deskripsi' => $processor['deskripsi'],
                'harga' => $processor['harga'],
                'diskon' => $processor['diskon'],
                'stok' => $processor['stok'],
                'berat' => $processor['berat'],
                'kondisi' => $processor['kondisi'],
                'kategori_id' => $processor['kategori_id'],
                'brand_id' => $processor['brand_id'],
                'socket_id' => $processor['socket_id'],
                'mobo_id' => null,
                'garansi_bulan' => $processor['garansi_bulan'],
                'is_aktif' => $processor['is_aktif'],
                'dilihat' => $processor['dilihat'],
            ]);
        }

        $savedMotherboards = [];
        foreach ($motherboards as $motherboard) {
            $savedMotherboards[] = Produk::create([
                'nama' => $motherboard['nama'],
                'slug' => Str::slug($motherboard['nama']),
                'sku' => $motherboard['sku'],
                'deskripsi' => $motherboard['deskripsi'],
                'harga' => $motherboard['harga'],
                'diskon' => $motherboard['diskon'],
                'stok' => $motherboard['stok'],
                'berat' => $motherboard['berat'],
                'kondisi' => $motherboard['kondisi'],
                'kategori_id' => $motherboard['kategori_id'],
                'brand_id' => $motherboard['brand_id'],
                'socket_id' => $motherboard['socket_id'],
                'mobo_id' => null,
                'garansi_bulan' => $motherboard['garansi_bulan'],
                'is_aktif' => $motherboard['is_aktif'],
                'dilihat' => $motherboard['dilihat'],
            ]);
        }

        // Mendapatkan motherboards berdasarkan socket untuk referensi mobo_id
        $lga1700Mobo = Produk::where('kategori_id', $motherboardKategori)
            ->where('socket_id', $lga1700Id)
            ->first()->id;

        $am5Mobo = Produk::where('kategori_id', $motherboardKategori)
            ->where('socket_id', $am5Id)
            ->first()->id;

        // Data untuk RAM
        $rams = [
            [
                'nama' => 'Corsair Vengeance RGB Pro 32GB (2x16GB) DDR5 6000MHz',
                'sku' => 'RAM-CORSAIR-32GB-DDR5-6000',
                'deskripsi' => 'Memori DDR5 dual-channel dengan RGB dan overclocking profile. Ideal untuk gaming dan kreasi konten.',
                'harga' => 2800000,
                'diskon' => 0,
                'stok' => 25,
                'berat' => 0.2,
                'kondisi' => 'Baru',
                'kategori_id' => $ramKategori,
                'brand_id' => $corsairId,
                'socket_id' => null,
                'mobo_id' => $am5Mobo, // Kompatibel dengan AM5 motherboard
                'garansi_bulan' => 24,
                'is_aktif' => true,
                'dilihat' => 320,
            ],
            [
                'nama' => 'Corsair Vengeance LPX 16GB (2x8GB) DDR4 3600MHz',
                'sku' => 'RAM-CORSAIR-16GB-DDR4-3600',
                'deskripsi' => 'Memori DDR4 dual-channel dengan profile rendah. Ideal untuk build compact.',
                'harga' => 1400000,
                'diskon' => 10,
                'stok' => 40,
                'berat' => 0.2,
                'kondisi' => 'Baru',
                'kategori_id' => $ramKategori,
                'brand_id' => $corsairId,
                'socket_id' => null,
                'mobo_id' => $lga1700Mobo, // Kompatibel dengan LGA1700 motherboard
                'garansi_bulan' => 24,
                'is_aktif' => true,
                'dilihat' => 350,
            ],
        ];

        // Data untuk VGA
        $vgas = [
            [
                'nama' => 'ASUS ROG Strix GeForce RTX 4080 16GB GDDR6X',
                'sku' => 'VGA-ASUS-RTX4080-16GB',
                'deskripsi' => 'Kartu grafis high-end dengan performa gaming premium dan ray tracing.',
                'harga' => 21500000,
                'diskon' => 0,
                'stok' => 8,
                'berat' => 1.8,
                'kondisi' => 'Baru',
                'kategori_id' => $vgaKategori,
                'brand_id' => $asusId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 400,
            ],
            [
                'nama' => 'MSI Gaming X Trio GeForce RTX 4070 12GB GDDR6X',
                'sku' => 'VGA-MSI-RTX4070-12GB',
                'deskripsi' => 'Kartu grafis performa tinggi dengan sistem pendingin triple-fan.',
                'harga' => 12500000,
                'diskon' => 5,
                'stok' => 15,
                'berat' => 1.6,
                'kondisi' => 'Baru',
                'kategori_id' => $vgaKategori,
                'brand_id' => $msiId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 380,
            ],
            [
                'nama' => 'Gigabyte AORUS Radeon RX 7900 XTX 24GB GDDR6',
                'sku' => 'VGA-GIGABYTE-RX7900XTX-24GB',
                'deskripsi' => 'Kartu grafis AMD premium dengan memori besar dan performa cooling superior.',
                'harga' => 18500000,
                'diskon' => 0,
                'stok' => 10,
                'berat' => 1.7,
                'kondisi' => 'Baru',
                'kategori_id' => $vgaKategori,
                'brand_id' => $gigabyteId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 36,
                'is_aktif' => true,
                'dilihat' => 340,
            ],
        ];

        // Data untuk storage (SSD dan HDD)
        $storages = [
            [
                'nama' => 'Samsung 980 PRO 2TB PCIe 4.0 NVMe SSD',
                'sku' => 'SSD-SAMSUNG-980PRO-2TB',
                'deskripsi' => 'SSD super cepat dengan interface PCIe 4.0 dan kecepatan baca hingga 7000MB/s.',
                'harga' => 4500000,
                'diskon' => 5,
                'stok' => 20,
                'berat' => 0.1,
                'kondisi' => 'Baru',
                'kategori_id' => $ssdKategori,
                'brand_id' => $samsungId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 60,
                'is_aktif' => true,
                'dilihat' => 280,
            ],
            [
                'nama' => 'Western Digital Black SN850X 1TB PCIe 4.0 NVMe SSD',
                'sku' => 'SSD-WD-SN850X-1TB',
                'deskripsi' => 'SSD gaming performa tinggi dengan heatsink dan kecepatan baca hingga 7300MB/s.',
                'harga' => 2800000,
                'diskon' => 0,
                'stok' => 25,
                'berat' => 0.1,
                'kondisi' => 'Baru',
                'kategori_id' => $ssdKategori,
                'brand_id' => $wdId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 60,
                'is_aktif' => true,
                'dilihat' => 260,
            ],
            [
                'nama' => 'Seagate BarraCuda 4TB 5400RPM SATA HDD',
                'sku' => 'HDD-SEAGATE-BARRACUDA-4TB',
                'deskripsi' => 'Hard drive penyimpanan massal untuk data, media, dan backup.',
                'harga' => 1800000,
                'diskon' => 0,
                'stok' => 30,
                'berat' => 0.7,
                'kondisi' => 'Baru',
                'kategori_id' => $hddKategori,
                'brand_id' => $seagateId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 24,
                'is_aktif' => true,
                'dilihat' => 220,
            ],
            [
                'nama' => 'Western Digital Blue 2TB 7200RPM SATA HDD',
                'sku' => 'HDD-WD-BLUE-2TB',
                'deskripsi' => 'Hard drive performa baik untuk penyimpanan umum dan gaming.',
                'harga' => 950000,
                'diskon' => 5,
                'stok' => 35,
                'berat' => 0.6,
                'kondisi' => 'Baru',
                'kategori_id' => $hddKategori,
                'brand_id' => $wdId,
                'socket_id' => null,
                'mobo_id' => null,
                'garansi_bulan' => 24,
                'is_aktif' => true,
                'dilihat' => 210,
            ],
        ];

        // Menyimpan produk RAM
        foreach ($rams as $ram) {
            Produk::create([
                'nama' => $ram['nama'],
                'slug' => Str::slug($ram['nama']),
                'sku' => $ram['sku'],
                'deskripsi' => $ram['deskripsi'],
                'harga' => $ram['harga'],
                'diskon' => $ram['diskon'],
                'stok' => $ram['stok'],
                'berat' => $ram['berat'],
                'kondisi' => $ram['kondisi'],
                'kategori_id' => $ram['kategori_id'],
                'brand_id' => $ram['brand_id'],
                'socket_id' => $ram['socket_id'],
                'mobo_id' => $ram['mobo_id'],
                'garansi_bulan' => $ram['garansi_bulan'],
                'is_aktif' => $ram['is_aktif'],
                'dilihat' => $ram['dilihat'],
            ]);
        }

        // Menyimpan produk VGA
        foreach ($vgas as $vga) {
            Produk::create([
                'nama' => $vga['nama'],
                'slug' => Str::slug($vga['nama']),
                'sku' => $vga['sku'],
                'deskripsi' => $vga['deskripsi'],
                'harga' => $vga['harga'],
                'diskon' => $vga['diskon'],
                'stok' => $vga['stok'],
                'berat' => $vga['berat'],
                'kondisi' => $vga['kondisi'],
                'kategori_id' => $vga['kategori_id'],
                'brand_id' => $vga['brand_id'],
                'socket_id' => $vga['socket_id'],
                'mobo_id' => $vga['mobo_id'],
                'garansi_bulan' => $vga['garansi_bulan'],
                'is_aktif' => $vga['is_aktif'],
                'dilihat' => $vga['dilihat'],
            ]);
        }

        // Menyimpan produk storage
        foreach ($storages as $storage) {
            Produk::create([
                'nama' => $storage['nama'],
                'slug' => Str::slug($storage['nama']),
                'sku' => $storage['sku'],
                'deskripsi' => $storage['deskripsi'],
                'harga' => $storage['harga'],
                'diskon' => $storage['diskon'],
                'stok' => $storage['stok'],
                'berat' => $storage['berat'],
                'kondisi' => $storage['kondisi'],
                'kategori_id' => $storage['kategori_id'],
                'brand_id' => $storage['brand_id'],
                'socket_id' => $storage['socket_id'],
                'mobo_id' => $storage['mobo_id'],
                'garansi_bulan' => $storage['garansi_bulan'],
                'is_aktif' => $storage['is_aktif'],
                'dilihat' => $storage['dilihat'],
            ]);
        }
    }
}
