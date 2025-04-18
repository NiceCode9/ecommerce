<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Socket;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Produk::orderBy('nama', 'asc')->get();
        return view('admin.produk.produk', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama', 'asc')->get();
        $sockets = Socket::orderBy('nama', 'asc')->get();
        $brands = Brand::orderBy('nama', 'asc')->get();
        $mobos = Produk::whereNotNull('mobo_id')->get();

        return view('admin.produk.create', compact('kategori', 'sockets', 'brands', 'mobos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_produk' => 'required|string|max:255',
    //         'slug' => 'required|string|max:255|unique:produk,slug',
    //         'sku' => 'required|string|max:255|unique:produk,sku',
    //         'deskripsi' => 'nullable|string',
    //         'harga' => 'required|numeric|min:0',
    //         'diskon' => 'nullable|numeric|min:0|max:100',
    //         'stok' => 'required|integer|min:0',
    //         'berat' => 'nullable|numeric|min:0',
    //         'kondisi' => 'required|string|in:new,refurbished,used',
    //         'kategori_id' => 'required|exists:kategori,id',
    //         'brand_id' => 'required|exists:brand,id',
    //         'socket_id' => 'nullable|exists:sockets,id',
    //         'mobo_id' => 'nullable|exists:produk,id',
    //         'garansi_bulan' => 'nullable|integer|min:0',
    //         'is_aktif' => 'nullable|boolean',
    //         'dilihat' => 'nullable|integer|min:0',
    //         'spesifikasi' => 'nullable|array',
    //         'spesifikasi.*.nama' => 'required|string|max:100',
    //         'spesifikasi.*.nilai' => 'required|string',
    //         'gambar' => 'nullable|array',
    //         'gambar.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $request->merge(['nama' => $request->input('nama_produk')]);

    //     $produk = Produk::create($request->except('spesifikasi', 'gambar'));
    //     $produk->spesifikasi()->createMany($request->input('spesifikasi', []));
    //     if ($request->hasFile('gambar')) {
    //         foreach ($request->file('gambar') as $file) {
    //             $path = $file->store('produk', 'public');
    //             $produk->gambar()->create(['path' => $path]);
    //         }
    //     }
    //     return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    // }
    public function store(Request $request)
    {
        // Validasi dasar
        $validationRules = [
            'nama_produk' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:produk,slug',
            'sku' => 'required|string|max:50|unique:produk,sku',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0|max:100',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:Baru,Bekas',
            'kategori_id' => 'required|exists:kategori,id',
            'brand_id' => 'nullable|exists:brands,id',
            'garansi_bulan' => 'nullable|integer|min:0',
            'is_aktif' => 'required|boolean',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama' => 'nullable|array',
            'nilai' => 'nullable|array',
        ];

        // Ambil kategori untuk penentuan validasi
        $kategori = Kategori::find($request->kategori_id);

        if ($kategori) {
            if ($kategori->tipe == 'processor' || $kategori->tipe == 'motherboard') {
                $validationRules['socket_id'] = 'required|exists:sockets,id';
                // motherboard tidak memerlukan mobo_id
            } else {
                // Untuk RAM dan komponen lain
                $validationRules['mobo_id'] = 'required|exists:produk,id';
            }
        }

        $request->validate($validationRules);

        // Proses pembuatan produk
        $request->merge(['nama' => $request->input('nama_produk')]);

        // Mengelola data input spesifikasi
        $spesifikasi = [];
        if ($request->has('nama') && $request->has('nilai')) {
            foreach ($request->nama as $key => $nama) {
                if (isset($request->nilai[$key])) {
                    $spesifikasi[] = [
                        'nama' => $nama,
                        'nilai' => $request->nilai[$key]
                    ];
                }
            }
        }

        $produk = Produk::create($request->except(['spesifikasi', 'gambar', 'nama_produk', 'nama', 'nilai', 'gambar_preview', 'is_utama', 'urutan']));

        // Tambahkan spesifikasi
        if (!empty($spesifikasi)) {
            $produk->spesifikasi()->createMany($spesifikasi);
        }

        // Proses gambar
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $index => $file) {
                $path = $file->store('produk', 'public');
                $isUtama = false;
                $urutan = $index + 1;

                if ($request->has('is_utama') && $request->is_utama == $index) {
                    $isUtama = true;
                }

                if ($request->has('urutan') && isset($request->urutan[$index])) {
                    $urutan = $request->urutan[$index];
                }

                $produk->gambar()->create([
                    'path' => $path,
                    'is_utama' => $isUtama,
                    'urutan' => $urutan
                ]);
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        //
    }
}
