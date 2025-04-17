<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand', compact('brands'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'is_processor' => 'required|boolean',
        ]);

        $request->merge([
            'slug' => Str::slug($request->nama),
        ]);
        $brand = Brand::create($request->all());
        return response()->json(['message' => 'Brand created successfully!', 'data' => $brand]);
    }

    public function show(Brand $brand)
    {
        //
    }

    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'is_processor' => 'required|boolean',
        ]);

        $brand->update($request->all());
        return response()->json(['message' => 'Brand updated successfully!', 'data' => $brand]);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully!']);
    }
}
