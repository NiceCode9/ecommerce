<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\BuildComponent;
use App\Models\Produk;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Socket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimulasiController extends Controller
{

    public function index()
    {
        $brands = Brand::where('is_processor', true)->get();
        $kategori = Kategori::whereHas('children')->where('tipe', 'general')->orderBy('nama', 'ASC')->get();
        $sockets = Socket::with('brand')->get();
        $processors = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'processor')])
            ->get();
        $motherboards = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'motherboard')])
            ->get();
        $rams = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'ram')])
            ->get();

        $initialData = [
            'sockets' => $sockets,
            'processors' => $processors,
            'motherboards' => $motherboards,
            'rams' => $rams
        ];

        return view('front.simulasi.index', compact('brands', 'kategori', 'sockets', 'processors', 'motherboards', 'rams', 'initialData'));
    }

    public function getSockets(Request $request)
    {
        $sockets = Socket::where('brand_id', $request->brand_id)->get();
        return response()->json($sockets);
    }

    public function getComponents(Request $request)
    {
        $type = $request->type;
        $query = Produk::with('brand', 'socket', 'kategori');

        // Filter untuk mode kompatibilitas
        if ($request->mode === 'compatibility') {
            if ($type === 'processor' && $request->socket_id) {
                $query->where('socket_id', $request->socket_id);
            }

            if ($type === 'motherboard' && $request->socket_id) {
                $query->where('socket_id', $request->socket_id);
            }

            if ($type === 'ram' && $request->motherboard_id) {
                $mobo = Produk::find($request->motherboard_id);
                $query->where('tipe_ram', $mobo->tipe_ram);
            }
        }

        $query->whereHas('kategori', fn($q) => $q->where('tipe', $type));

        return response()->json($query->get());
    }

    public function saveBuild(Request $request)
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 2));
        $kode = 'SML-' . $initials . '-' . strtoupper(uniqid());
        $name = $kode;
        $slug = Str::slug($name);

        $build = Build::create([
            'kode' => $kode,
            'user_id' => $user->id,
            'name' => $name,
            'slug' => $slug,
            'description' => $request->description,
            'total_price' => 0,
            'mode' => $request->mode,
            'status' => 'draft',
            'is_public' => false,
        ]);
    }
}
