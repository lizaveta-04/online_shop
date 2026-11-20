<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    /**
     * Страница профиля.
     */
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    /**
     * Обновление профиля.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Профиль успешно обновлён!');
    }

    /**
     * История заказов.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->paginate(15);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Детали заказа.
     */
    public function orderShow($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('items.product')->findOrFail($id);

        return view('profile.order-show', compact('order'));
    }
}
