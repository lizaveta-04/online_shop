@extends('layouts.admin')

@section('title', 'Заказы')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li>Заказы</li>
</ol>

<div class="admin-header">
    <h1>Управление заказами</h1>
</div>

<form method="GET" action="{{ route('admin.orders.index') }}" class="filters">
    <div class="form-group">
        <input type="text" name="search" placeholder="🔍 Поиск по № заказа / пользователю..." value="{{ request('search') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
    </div>
    <div class="form-group">
        <select name="status" class="form-select">
            <option value="">Все статусы</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Новый</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>В обработке</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Отправлен</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Доставлен</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Отменён</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height: 42px;">Найти</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px;">№ заказа</th>
                <th>Пользователь</th>
                <th>Товаров</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>
                    {{ $order->user->name }}<br>
                    <span style="font-size: 12px; color: #7a6a58;">{{ $order->user->email }}</span>
                </td>
                <td>{{ $order->items->count() }}</td>
                <td><strong>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</strong></td>
                <td>
                    <span class="badge badge-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending') Новый @break
                            @case('processing') В обработке @break
                            @case('shipped') Отправлен @break
                            @case('delivered') Доставлен @break
                            @case('cancelled') Отменён @break
                        @endswitch
                    </span>
                </td>
                <td>{{ $order->created_at->format('d.m.Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Подробнее</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 40px;">Заказы не найдены.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->appends(request()->query())->links() }}
@endsection
