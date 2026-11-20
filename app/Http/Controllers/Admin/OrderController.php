<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Shop\Order;

class OrderController extends BaseController
{
    /**
     * Список заказов.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Детали заказа.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Смена статуса заказа.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', "Статус заказа обновлён на «{$validated['status']}».");
    }

    /**
     * Отмена заказа.
     */
    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ отменён.');
    }
}
