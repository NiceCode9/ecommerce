<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Socket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sockets = Socket::all();
        $brands = Brand::all();
        return view('admin.socket', compact('sockets', 'brands'));
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
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
        ]);

        Socket::create($request->all());

        return response()->json(['message' => 'Socket berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Socket $socket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Socket $socket)
    {
        return response()->json($socket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Socket $socket)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $socket->update($request->all());

        return response()->json(['message' => 'Socket berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socket $socket)
    {
        $socket->delete();

        return response()->json(['message' => 'Socket berhasil dihapus.']);
    }
}
