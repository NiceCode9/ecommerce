<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_telepon',
        'tanggal_lahir',
        'foto_profil',
        'jenis_kelamin',
        'alamat_utama',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'date'
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'pelanggan';
    }

    // Relasi dengan alamat utama
    public function alamatUtama()
    {
        return $this->belongsTo(Alamat::class, 'alamat_utama');
    }

    // Relasi dengan semua alamat
    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'pengguna_id');
    }

    // Relasi dengan keranjang
    public function carts()
    {
        return $this->hasMany(Keranjang::class, 'pengguna_id');
    }

    // Relasi dengan wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'pengguna_id');
    }

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'pengguna_id');
    }

    // Relasi dengan ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'pengguna_id');
    }

    // Relasi dengan build
    public function builds()
    {
        return $this->hasMany(Build::class, 'user_id');
    }
}
