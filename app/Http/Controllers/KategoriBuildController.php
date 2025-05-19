<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuild;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriBuildController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriBuild::orderBy('nama', 'asc')->get();
        return view('admin.buildKategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $slug = Str::slug($request->nama);

        KategoriBuild::create([
            'nama' => $request->nama,
            'slug' => $slug,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori Build Berhasil di simpan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBuild $kategoriBuild)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $kategoriBuild = KategoriBuild::where('slug', $slug)->first();
        return response()->json($kategoriBuild);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategoriBuild = KategoriBuild::where('slug', $id)->first();
        $kategoriBuild->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Kategori Build Berhasil di update',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $kategoriBuild = KategoriBuild::where('slug', $slug)->first();
        $kategoriBuild->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kategori Build Berhasil di hapus',
        ]);
    }
}
