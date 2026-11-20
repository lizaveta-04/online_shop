<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Shop\Order;
use App\Models\Shop\Product;

class CheckoutController extends BaseController
{
    /**
     * Страница оформления заказа.
     */
    public function create()
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

        return view('checkout.index', compact('items', 'total'));
    }

    /**
     * Сохранение заказа.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Корзина пуста.');
        }

        $total = 0;
        $items = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product) {
                return back()->with('error', 'Один из товаров больше недоступен. Удалите его из корзины и попробуйте снова.');
            }

            $quantity = (int) $item['quantity'];

            if ($quantity < 1 || $product->stock < $quantity) {
                return back()->with('error', "Недостаточно товара «{$product->name}» на складе.");
            }

            $subtotal = $product->price * $quantity;
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ];
            $total += $subtotal;
        }

        // Заказ и списание остатков выполняются атомарно
        $order = DB::transaction(function () use ($items, $total, $validated) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pending',
                'address' => $validated['address'],
                'comment' => $validated['comment'] ?? null,
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
                Product::whereKey($item['product_id'])->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        // Очистка корзины
        Session::forget('cart');

        return redirect()->route('profile.orders')
            ->with('success', "Заказ №{$order->id} успешно оформлен!");
    }
}
