<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\BuildComponent;
use App\Models\Produk;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\KategoriBuild;
use App\Models\Socket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        // $rams = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'memory')])
        //     ->get();
        $rams = Produk::whereHas('kategori', function ($query) {
            $query->where('tipe', 'memory');
        })->get();

        $initialData = [
            'sockets' => $sockets,
            'processors' => $processors,
            'motherboards' => $motherboards,
            'rams' => $rams
        ];

        return view('front.simulasi.index', compact('brands', 'kategori', 'sockets', 'processors', 'motherboards', 'rams', 'initialData'));
    }

    public function adminIndex()
    {
        $data = Build::whereNotNull('kategori_id')->get();
        return view('admin.rakitan.index', compact('data'));
    }

    public function adminCreate()
    {
        $brands = Brand::where('is_processor', true)->get();
        $kategori = Kategori::whereHas('children')->where('tipe', 'general')->orderBy('nama', 'ASC')->get();
        $sockets = Socket::with('brand')->get();
        $processors = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'processor')])
            ->get();
        $motherboards = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'motherboard')])
            ->get();
        // $rams = Produk::with(['kategori' => fn($q) => $q->where('tipe', 'memory')])
        //     ->get();
        $rams = Produk::whereHas('kategori', function ($query) {
            $query->where('tipe', 'memory');
        })->get();
        $kategoriBuilds = KategoriBuild::orderBy('nama', 'asc')->get();


        return view('admin.rakitan.create', compact('brands', 'kategori', 'kategoriBuilds', 'sockets', 'processors', 'motherboards', 'rams'));
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

            if ($type === 'memory' && $request->motherboard_id) {
                // $mobo = Produk::find($request->motherboard_id);
                $query->where('mobo_id', $request->motherboard_id);
            }
        }

        $query->whereHas('kategori', fn($q) => $q->where('tipe', $type));

        return response()->json($query->get());
    }

    public function saveBuildAdmin(Request $request)
    {
        // $request->validate([
        //     'description' => 'required',
        //     'brand_id' => 'required',
        //     'socket_id' => 'required',
        //     'kategoriBuild_id' => 'required',
        // ]);

        DB::beginTransaction();
        try {
            $kategoriBuild = KategoriBuild::find($request->kategoriBuild_id);
            $words = explode(' ', $kategoriBuild->nama);
            $initial = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initial .= strtoupper($word[0]);
                }
            }
            $kode = 'SML-' . $initial . '-' . strtoupper(uniqid());
            $name = $kode;
            $slug = Str::slug($name);
            $componentsData = [];

            foreach ($request->components as $componentType => $component) {
                $componentsData[] = [
                    'produk_id' => $component['id'],
                    'component_type' => $componentType,
                    'quantity' => $component['quantity'],
                    'subtotal' => $component['subtotal'],
                ];
            }

            $build = Build::create([
                'kode' => $kode,
                'user_id' => auth()->user()->id,
                'kategori_id' => $request->kategoriBuild_id,
                'name' => $kode,
                'slug' => $slug,
                'description' => $request->description,
                'total_price' => $request->total_price,
                'mode' => $request->mode,
                'status' => 'published',
                'brand_id' => $request->brand_id,
                'socket_id' => $request->socket_id,
            ]);

            foreach ($componentsData as $component) {
                $build->components()->create($component);
            }
            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rakitan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function saveBuild(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:compatibility,free',
            'components' => 'required|array',
            'components.*.id' => 'required|exists:produk,id',
            'components.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $initials = strtoupper(substr($user->name, 0, 2));
            $kode = 'SML-' . $initials . '-' . strtoupper(uniqid());
            $name = $kode;
            $slug = Str::slug($name);

            // Calculate total price
            $totalPrice = 0;
            $componentsData = [];

            foreach ($request->components as $componentType => $component) {
                $product = Produk::findOrFail($component['id']);
                $subtotal = $product->harga_setelah_diskon * $component['quantity'];

                $componentsData[] = [
                    'produk_id' => $component['id'],
                    'component_type' => $componentType,
                    'quantity' => $component['quantity'],
                    'subtotal' => $subtotal
                ];

                $totalPrice += $subtotal;
            }

            // Create the build
            $build = Build::create([
                'kode' => $kode,
                'user_id' => $user->id,
                'name' => $name,
                'slug' => $slug,
                'description' => $request->description ?? 'Rakitan PC baru',
                'total_price' => $totalPrice,
                'mode' => $request->mode,
                'status' => 'draft',
            ]);

            // Add components to the build
            foreach ($componentsData as $component) {
                $build->components()->create($component);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rakitan berhasil disimpan!',
                'data' => [
                    'build_id' => $build->id,
                    'kode' => $build->kode,
                    'total_price' => $totalPrice
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rakitan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        $builds = Build::with('components.produk')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('front.simulasi.list', compact('builds'));
    }

    public function show(Build $build)
    {
        // Authorization - hanya pemilik yang bisa melihat
        if ($build->user_id !== auth()->id()) {
            abort(403);
        }

        $build->load('components.produk.kategori');

        return view('front.simulasi.show', compact('build'));
    }

    public function destroy(Build $build)
    {
        // Authorization - hanya pemilik yang bisa menghapus
        if ($build->user_id !== auth()->id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $build->components()->delete();
            $build->delete();
            DB::commit();

            return redirect()->route('simulasi.list')
                ->with('success', 'Rakitan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus rakitan: ' . $e->getMessage());
        }
    }
}
