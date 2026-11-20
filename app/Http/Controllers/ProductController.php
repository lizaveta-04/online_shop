<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop\Product;
use App\Models\Shop\Category;

class ProductController extends BaseController
{
    /**
     * Отображение каталога товаров.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        // Поиск по названию (LIKE регистронезависим благодаря ci-collation в MySQL)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Страница отдельного товара.
     */
    public function show(Product $product)
    {
        // Неактивный товар доступен только администратору
        if (!$product->is_active && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }

        $product->load('category');

        return view('products.show', compact('product'));
    }
}
