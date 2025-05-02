<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\GambarProduk;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Socket;;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        $mobos = Produk::whereHas('kategori', function ($q) {
            $q->where('tipe', 'motherboard');
        })->get();

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
            'kondisi' => 'required|in:baru,bekas',
            'kategori_id' => 'required|exists:kategori,id',
            'brand_id' => 'nullable|exists:brands,id',
            'garansi_bulan' => 'nullable|integer|min:0',
            'is_aktif' => 'required|boolean',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_spek.*' => 'nullable|array',
            'nilai' => 'nullable|array',
        ];

        // Ambil kategori untuk penentuan validasi
        $kategori = Kategori::find($request->kategori_id);

        if ($kategori) {
            if ($kategori->tipe == 'processor' || $kategori->tipe == 'motherboard') {
                $validationRules['socket_id'] = 'required|exists:sockets,id';
            }
            if ($kategori->tipe == 'memory') {
                $validationRules['mobo_id'] = 'required|exists:produk,id';
            }
        }

        $request->validate($validationRules);

        // Proses pembuatan produk
        $harga_setelah_diskon = $request->diskon > 0
            ? $request->harga - ($request->harga * $request->diskon / 100)
            : $request->harga;
        $request->merge([
            'nama' => $request->input('nama_produk'),
            'harga_setelah_diskon' => $harga_setelah_diskon
        ]);

        // Mengelola data input spesifikasi
        $spesifikasi = [];
        if ($request->has('nama_spek') && $request->has('nilai')) {
            foreach ($request->nama_spek as $key => $nama) {
                if (isset($request->nilai[$key])) {
                    $spesifikasi[] = [
                        'nama_spek' => $nama,
                        'nilai' => $request->nilai[$key]
                    ];
                }
            }
        }

        $produk = Produk::create($request->except(['spesifikasi', 'gambar', 'nama_produk', 'nama_spek', 'nilai', 'gambar_preview', 'is_utama', 'urutan']));

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
                    'gambar' => $path,
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
        $kategori = Kategori::orderBy('nama', 'asc')->get();
        $sockets = Socket::orderBy('nama', 'asc')->get();
        $brands = Brand::orderBy('nama', 'asc')->get();
        $mobos = Produk::whereNotNull('mobo_id')->get();
        $produk->load('spesifikasi', 'gambar');
        // $produk->spesifikasi = $produk->spesifikasi->map(function ($item) {
        //     return [
        //         'nama' => $item->nama_spek,
        //         'nilai' => $item->nilai
        //     ];
        // });
        // $produk->gambar = $produk->gambar->map(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'path' => $item->gambar,
        //         'is_utama' => $item->is_utama,
        //         'urutan' => $item->urutan
        //     ];
        // });
        // $produk->gambar_preview = $produk->gambar->where('is_utama', true)->first();
        // if ($produk->gambar_preview) {
        //     $produk->gambar_preview = $produk->gambar_preview["path"];
        // } else {
        //     $produk->gambar_preview = null;
        // }
        // $produk->is_utama = $produk->gambar->where('is_utama', true)->first();
        // if ($produk->is_utama) {
        //     $produk->is_utama = $produk->is_utama['id'];
        // } else {
        //     $produk->is_utama = null;
        // }

        return view('admin.produk.edit', compact('produk', 'kategori', 'sockets', 'brands', 'mobos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validationRules = [
            'nama_produk' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:produk,slug,' . $produk->id,
            'sku' => 'required|string|max:50|unique:produk,sku,' . $produk->id,
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0|max:100',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baru,bekas',
            'kategori_id' => 'required|exists:kategori,id',
            'brand_id' => 'nullable|exists:brands,id',
            'garansi_bulan' => 'nullable|integer|min:0',
            'is_aktif' => 'required|boolean',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_spek.*' => 'nullable|array',
            'nilai' => 'nullable|array',
        ];

        // Ambil kategori untuk penentuan validasi
        $kategori = Kategori::find($request->kategori_id);

        if ($kategori) {
            if ($kategori->tipe == 'processor' || $kategori->tipe == 'motherboard') {
                $validationRules['socket_id'] = 'required|exists:sockets,id';
            }
            if ($kategori->tipe == 'memory') {
                $validationRules['mobo_id'] = 'required|exists:produk,id';
            }
        }

        $request->validate($validationRules);

        // Proses update produk
        $harga_setelah_diskon = $request->diskon > 0
            ? $request->harga - ($request->harga * $request->diskon / 100)
            : $request->harga;
        $request->merge([
            'nama' => $request->input('nama_produk'),
            'harga_setelah_diskon' => $harga_setelah_diskon
        ]);

        // Mengelola data input spesifikasi
        $spesifikasi = [];
        if ($request->has('nama_spek') && $request->has('nilai')) {
            foreach ($request->nama_spek as $key => $nama) {
                if (isset($request->nilai[$key])) {
                    $spesifikasi[] = [
                        'nama_spek' => $nama,
                        'nilai' => $request->nilai[$key]
                    ];
                }
            }
        }

        // Update data produk
        $produk->update($request->except(['spesifikasi', 'gambar', 'nama_produk', 'nama_spek', 'nilai', 'gambar_preview', 'is_utama', 'urutan']));

        // Hapus spesifikasi lama dan tambahkan yang baru
        $produk->spesifikasi()->delete();
        if (!empty($spesifikasi)) {
            $produk->spesifikasi()->createMany($spesifikasi);
        }

        // Proses gambar baru
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
                    'gambar' => $path,
                    'is_utama' => $isUtama,
                    'urutan' => $urutan
                ]);
            }
        }

        // Update gambar utama jika ada perubahan
        if ($request->has('is_utama_existing')) {
            $produk->gambar()->update(['is_utama' => false]);
            $produk->gambar()->where('id', $request->is_utama_existing)->update(['is_utama' => true]);
        }

        // Update urutan gambar yang sudah ada
        if ($request->has('urutan_existing')) {
            foreach ($request->urutan_existing as $id => $urutan) {
                $produk->gambar()->where('id', $id)->update(['urutan' => $urutan]);
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        foreach ($produk->gambar as $gambar) {
            if (File::exists('public/' . $gambar->path)) {
                File::delete('public/' . $gambar->path);
            }
            $gambar->delete();
        }

        $produk->spesifikasi()->delete();

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyGambar($id)
    {
        $gambar = GambarProduk::findOrFail($id);
        if (File::exists('public/' . $gambar->path)) {
            File::delete('public/' . $gambar->path);
        }
        $gambar->delete();

        return response()->json(['success' => true]);
    }

    public function getGambar($id)
    {
        $produk = Produk::with('gambar')->findOrFail($id);

        $gambar = $produk->gambar->map(function ($item) {
            return [
                'id' => $item->id,
                'urutan' => $item->urutan,
                'path' => asset('storage/' . $item->gambar),
                'is_utama' => $item->is_utama
            ];
        });

        return response()->json([
            'gambar' => $gambar
        ]);
    }

    public function setGambarUtama($id)
    {
        $gambar = GambarProduk::findOrFail($id);

        GambarProduk::where('produk_id', $gambar->produk_id)
            ->update(['is_utama' => false]);

        $gambar->update(['is_utama' => true]);

        return response()->json(['message' => 'Gambar berhasil diatur sebagai utama']);
    }

    /**
     * Upload gambar tambahan
     */
    public function uploadGambar(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        $path = $request->file('gambar')->store('produk', 'public');

        // Jika ini gambar pertama, jadikan sebagai utama
        $isUtama = $produk->gambar->isEmpty();

        $gambar = $produk->gambar()->create([
            'gambar' => $path,
            'is_utama' => $isUtama,
            'urutan' => $produk->gambar->count() + 1
        ]);

        return response()->json([
            'message' => 'Gambar berhasil diupload',
            'gambar' => $gambar
        ]);
    }

    /**
     * Update urutan gambar
     */
    public function updateUrutanGambar(Request $request)
    {
        $request->validate([
            'urutan' => 'required|array',
            'urutan.*.id' => 'required|exists:gambar_produk,id',
            'urutan.*.urutan' => 'required|integer|min:1'
        ]);

        foreach ($request->urutan as $item) {
            GambarProduk::where('id', $item['id'])
                ->update(['urutan' => $item['urutan']]);
        }

        return response()->json(['message' => 'Urutan gambar berhasil diupdate']);
    }

    public function quickView($id)
    {
        $product = Produk::with(['gambarUtama', 'kategori', 'gambar'])
            ->findOrFail($id);

        return view('front.products.quick-view', compact('product'));
    }
}
