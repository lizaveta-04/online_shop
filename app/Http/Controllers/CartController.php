<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Shop\Product;

class CartController extends BaseController
{
    /**
     * Показать корзину.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Добавить товар в корзину.
     */
    public function add(Request $request, Product $product)
    {
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return back()->with('error', 'Укажите корректное количество.');
        }

        if ($product->stock < $quantity) {
            return back()->with('error', 'Недостаточно товара на складе.');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = ['quantity' => $quantity];
        }

        Session::put('cart', $cart);

        return back()->with('success', "Товар «{$product->name}» добавлен в корзину.");
    }

    /**
     * Обновить количество товара в корзине.
     */
    public function update(Request $request, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = (int) $request->input('quantity', 1);

            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $product = Product::find($productId);
                if ($product && $product->stock >= $quantity) {
                    $cart[$productId]['quantity'] = $quantity;
                } else {
                    return back()->with('error', 'Недостаточно товара на складе.');
                }
            }

            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    /**
     * Удалить товар из корзины.
     */
    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return redirect()->route('cart.index');
    }
}
