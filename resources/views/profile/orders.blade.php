@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li><a href="{{ route('profile.show') }}">Личный кабинет</a></li>
    <li>Мои заказы</li>
</ol>

<h1 style="color: #d86800; margin: 10px 0 25px;">Мои заказы</h1>

@forelse($orders as $order)
<div class="order-history-item">
    <div class="order-history-header">
        <div>
            <span class="order-id">Заказ №{{ $order->id }}</span>
            <span class="badge badge-{{ $order->status }}" style="margin-left: 10px;">
                @switch($order->status)
                    @case('pending') Новый @break
                    @case('processing') В обработке @break
                    @case('shipped') Отправлен @break
                    @case('delivered') Доставлен @break
                    @case('cancelled') Отменён @break
                @endswitch
            </span>
        </div>
        <span class="order-date">{{ $order->created_at->format('d.m.Y') }}</span>
    </div>
    <div class="order-items-list">
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach($order->items as $item)
            <li>• {{ $item->product->name ?? 'Товар удалён' }} — {{ number_format($item->price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</li>
            @endforeach
        </ul>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div class="order-total" style="margin: 0; padding: 0; border: none;">{{ number_format($order->total_price, 0, ',', ' ') }} ₽</div>
        <a href="{{ route('profile.order.show', $order->id) }}" class="btn btn-sm btn-outline">Подробнее</a>
    </div>
</div>
@empty
<div class="empty-state">
    <div class="empty-icon">📦</div>
    <p>У вас пока нет заказов.</p>
    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
</div>
@endforelse

{{ $orders->links() }}
@endsection
