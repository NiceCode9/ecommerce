<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    // public function index(){
    //     $wishlists = auth()->user()->wishlists()->with('produk.gambarUtama')->paginate(10);
    //     return view('front.wishlist.wishlist', compact('wishlists'));
    // }
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'latest');
        
        $wishlists = auth()->user()->wishlists()
            ->with('produk.gambarUtama')
            ->join('produk', 'wishlists.produk_id', '=', 'produk.id')
            ->select('wishlists.*');

        switch ($sortBy) {
            case 'price_asc': $wishlists->orderBy('produk.harga', 'asc'); break;
            case 'price_desc': $wishlists->orderBy('produk.harga', 'desc'); break;
            case 'name_asc': $wishlists->orderBy('produk.nama', 'asc'); break;
            case 'name_desc': $wishlists->orderBy('produk.nama', 'desc'); break;
            default: $wishlists->latest('wishlists.created_at');
        }

        return view('front.wishlist.wishlist', [
            'wishlists' => $wishlists->paginate(2),
            'currentSort' => $sortBy // Kirim ke view
        ]);
    }

    public function ajaxIndex()
    {
        $wishlists = auth()->user()->wishlists()
                        ->with('produk.gambarUtama')
                        ->paginate(10);

        return view('front.wishlist._items', compact('wishlists'));
    }
    
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id'
        ]);

        $wishlist = Wishlist::where('pengguna_id', auth()->id())
            ->where('produk_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'count' => auth()->user()->wishlists()->count()]);
        } else {
            Wishlist::create([
                'pengguna_id' => auth()->id(),
                'produk_id' => $request->product_id
            ]);
            return response()->json(['status' => 'added', 'count' => auth()->user()->wishlists()->count()]);
        }
    }

    public function remove($id)
    {
        $wishlist = Wishlist::where('pengguna_id', auth()->id())
                    ->findOrFail($id);
        $wishlist->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Removed from wishlist',
            'count' => auth()->user()->wishlists()->count()
        ]);
    }

    public function sort(Request $request)
    {
        $sortBy = $request->input('sort_by', 'latest');
        
        $wishlists = auth()->user()->wishlists()
            ->with('produk.gambarUtama')
            ->join('produk', 'wishlists.produk_id', '=', 'produk.id')
            ->select('wishlists.*'); // Penting untuk avoid column ambiguity

        switch ($sortBy) {
            case 'price_asc':
                $wishlists->orderBy('produk.harga', 'asc');
                break;
            case 'price_desc':
                $wishlists->orderBy('produk.harga', 'desc');
                break;
            case 'name_asc':
                $wishlists->orderBy('produk.nama', 'asc');
                break;
            case 'name_desc':
                $wishlists->orderBy('produk.nama', 'desc');
                break;
            case 'latest':
            default:
                $wishlists->latest('wishlists.created_at');
        }

        $wishlists = $wishlists->paginate(10);

        return response()->json([
            'data' => $wishlists->items(),
            'links' => $wishlists->links('front.pagination')->toHtml()
        ]);
    }
}
