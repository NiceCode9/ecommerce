<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alamat;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a customer user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567890',
            'tanggal_lahir' => '1990-01-01',
            'foto_profil' => null,
            'jenis_kelamin' => 'Laki-Laki',
            'alamat_utama' => null, // Will be updated after creating addresses
            'role' => 'pelanggan',
        ]);

        // Create multiple addresses for the user
        $addresses = [
            [
                'pengguna_id' => $user->id,
                'nama_penerima' => 'John Doe',
                'nomor_telepon' => '081234567890',
                'alamat_lengkap' => 'Jl. Mawar No. 1',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Cicendo',
                'kelurahan' => 'Pasirkaliki',
                'kode_pos' => '40171',
                'catatan' => 'Rumah',
                'is_utama' => true,
            ],
            [
                'pengguna_id' => $user->id,
                'nama_penerima' => 'John Doe',
                'nomor_telepon' => '081234567890',
                'alamat_lengkap' => 'Jl. Melati No. 2',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Coblong',
                'kelurahan' => 'Dago',
                'kode_pos' => '40135',
                'catatan' => 'Kantor',
                'is_utama' => false,
            ],
        ];

        foreach ($addresses as $address) {
            Alamat::create($address);
        }

        // Update the user's primary address
        $user->update(['alamat_utama' => Alamat::where('pengguna_id', $user->id)->where('is_utama', true)->first()->id]);
    }
}
