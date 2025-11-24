<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function products()
    // {
    //     // $products = Product::all();
    //     $products = Product::whereHas('categories', function ($query) {
    //         $query->where('name', 'LIKE', '%Phone%');
    //     })
    //     ->with('categories')
    //     ->get();
    //     return response()->json($products);
    // } // manyToMany

    // public function productsWithCategories()
    // {
    //     $products = Product::with('categories')->get();
    //     return response()->json($products);
    // }

    public function productsWithCategories()
    {
        $products = Product::whereHas('categories', function ($query) {
            $query->where('name', 'Accessories');  // whereHas condition
        })
        // ->where('id', '>=', 3)
        ->get();
        return response()->json($products);
    }

    public function selfReferentialProducts()
    {
        $tree = Category::with('children')
        // ->where('parent_id', null)
        ->whereNull('parent_id')
        ->get();
        // return response()->json($tree);

        $withParent = Category::with('parent')
        ->whereNotNull('parent_id')
        ->get();
        // return response()->json($withParents);

        return response()->json([
        'parent_with_children' => $tree,
        'children_with_parent' => $withParent,
        ]);
    }
}
