@extends('layouts.app')

@section('title', 'Заказ №' . $order->id)

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li><a href="{{ route('profile.show') }}">Личный кабинет</a></li>
    <li><a href="{{ route('profile.orders') }}">Мои заказы</a></li>
    <li>Заказ №{{ $order->id }}</li>
</ol>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
    <h1 style="color: #d86800; margin: 0;">Заказ №{{ $order->id }}</h1>
    <span class="badge badge-{{ $order->status }}" style="font-size: 14px; padding: 6px 14px;">
        @switch($order->status)
            @case('pending') Новый @break
            @case('processing') В обработке @break
            @case('shipped') Отправлен @break
            @case('delivered') Доставлен @break
            @case('cancelled') Отменён @break
        @endswitch
    </span>
</div>

<div class="order-details-grid">
    <div class="order-details-info">
        <h2 style="color: #d86800; font-size: 18px; margin: 0 0 15px;">Состав заказа</h2>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Кол-во</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Товар удалён' }}</td>
                        <td>{{ number_format($item->price, 0, ',', ' ') }} ₽</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="order-total" style="margin-top: 15px;">Итого: {{ number_format($order->total_price, 0, ',', ' ') }} ₽</div>
    </div>

    <div class="order-details-sidebar">
        <div class="card">
            <h3 style="margin: 0 0 10px; color: #d86800; font-size: 15px;">📍 Доставка</h3>
            <p style="margin: 0; font-size: 14px; line-height: 1.6;">
                {{ $order->address ?? 'Адрес не указан' }}<br>
                @if($order->comment)
                    <em style="font-size: 13px; color: #7a6a58;">Комментарий: {{ $order->comment }}</em>
                @endif
            </p>
        </div>

        <div class="card">
            <h3 style="margin: 0 0 10px; color: #d86800; font-size: 15px;">📊 История статусов</h3>
            <div class="order-status-timeline">
                @php
                    $statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIdx = array_search($order->status, $statusOrder);
                    $cancelled = $order->status === 'cancelled';
                @endphp
                @if($cancelled)
                    <div class="status-step">
                        <div class="status-dot" style="background-color: #c62828;"></div>
                        <div class="status-text">
                            <strong>Заказ отменён</strong>
                            <span>{{ $order->updated_at->format('d.m.Y, H:i') }}</span>
                        </div>
                    </div>
                @else
                    @foreach(['Новый', 'В обработке', 'Отправлен', 'Доставлен'] as $idx => $label)
                        <div class="status-step">
                            <div class="status-dot {{ $idx < $currentIdx ? 'completed' : ($idx === $currentIdx ? 'active' : '') }}"></div>
                            <div class="status-text">
                                <strong>{{ $label }}</strong>
                                <span>{{ $idx <= $currentIdx ? $order->updated_at->format('d.m.Y, H:i') : '—' }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<a href="{{ route('profile.orders') }}" class="btn btn-outline" style="margin-top: 20px;">← Вернуться к заказам</a>
@endsection
