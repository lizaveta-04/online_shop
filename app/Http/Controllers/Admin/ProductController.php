<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Shop\Product;
use App\Models\Shop\Category;

class ProductController extends BaseController
{
    /**
     * Сформировать уникальный slug для товара.
     */
    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $counter = 2;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Список товаров.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('active')) {
            if ($request->active === '1') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Форма создания/редактирования.
     */
    public function create()
    {
        $categories = Category::all();
        $product = null;

        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Сохранение нового товара.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->makeUniqueSlug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }

    /**
     * Показать товар.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Форма редактирования.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Обновление товара.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Slug меняем только при изменении названия, чтобы не ломать ссылки
        if ($product->name !== $validated['name']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['name'], $product->id);
        }

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлён.');
    }

    /**
     * Удаление товара (soft delete).
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар перемещён в корзину.');
    }
}
