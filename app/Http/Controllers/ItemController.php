<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Item_condition;
use App\Models\User;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // キーワード検索の処理を追加(マイページでも保持)
        $keyword = $request->input('keyword');

        if(!empty($keyword)) {
            session(['keyword' => $keyword]);
        } else {
            session()->forget('keyword');
        };
        $query = Item::withoutOwner()->with(['categories', 'itemCondition', 'order']);
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }
        $items = $query->get();
        return view('index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::withoutOwner()->with(['categories', 'itemCondition', 'seller', 'order'])->findOrFail($id);
        return view('item', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Item_condition::all();
        return view('exhibition', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('item_images', 'public');

        $item = Item::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'brand' => $request->input('brand'),
            'price' => $request->input('price'),
            'image_url' => $path,
            'condition_id' => $request->input('condition_id'),
            'seller_id' => auth()->id(),
        ]);

        $item->categories()->attach($request->input('category_id'));

        return redirect()->route('items.show', $item->id)->with('success', '商品が出品されました。');
    }
}
