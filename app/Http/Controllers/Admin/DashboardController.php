<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Shop\Product;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\Application;
use App\Models\User;

class DashboardController extends BaseController
{
    /**
     * Страница обзора (дашборд).
     */
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'applications' => Application::where('status', 'new')->count(),
            'users' => User::count(),
            'revenue' => Order::whereNotIn('status', ['cancelled'])->sum('total_price'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $newApplications = Application::where('status', 'new')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'newApplications'));
    }
}
