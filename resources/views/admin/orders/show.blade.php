@extends('layouts.admin')

@section('title', 'Заказ №' . $order->id)

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li><a href="{{ route('admin.orders.index') }}">Заказы</a></li>
    <li>Заказ №{{ $order->id }}</li>
</ol>

<div class="admin-header">
    <h1>Заказ №{{ $order->id }}</h1>
    <div style="display: flex; gap: 10px; align-items: center;">
        <span class="badge badge-{{ $order->status }}" style="font-size: 14px; padding: 6px 14px;">
            @switch($order->status)
                @case('pending') Новый @break
                @case('processing') В обработке @break
                @case('shipped') Отправлен @break
                @case('delivered') Доставлен @break
                @case('cancelled') Отменён @break
            @endswitch
        </span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">← К списку</a>
    </div>
</div>

<div class="order-details-grid">
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">📦 Состав заказа</div>
            <div class="card-body" style="padding: 0;">
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
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold; color: #d86800; font-size: 16px;">Итого:</td>
                            <td style="font-weight: bold; color: #d86800; font-size: 16px;">{{ number_format($order->total_price, 0, ',', ' ') }} ₽</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">📍 Данные доставки</div>
            <div class="card-body">
                <p style="margin: 0 0 8px;"><strong>Адрес:</strong> {{ $order->address ?? 'Не указан' }}</p>
                @if($order->comment)
                    <p style="margin: 0; color: #7a6a58; font-size: 14px;"><strong>Комментарий:</strong> {{ $order->comment }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="order-details-sidebar">
        <div class="card">
            <h3 style="margin: 0 0 12px; color: #d86800; font-size: 15px;">👤 Покупатель</h3>
            <p style="margin: 0 0 6px;"><strong>{{ $order->user->name }}</strong></p>
            <p style="margin: 0 0 6px; font-size: 14px; color: #7a6a58;">{{ $order->user->email }}</p>
            @if($order->user->phone)
                <p style="margin: 0; font-size: 14px; color: #7a6a58;">{{ $order->user->phone }}</p>
            @endif
        </div>

        <div class="card">
            <h3 style="margin: 0 0 12px; color: #d86800; font-size: 15px;">⚙️ Действия</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                @csrf
                @method('PATCH')
                <div class="form-group" style="margin: 0;">
                    <label for="status" style="font-size: 13px; margin-bottom: 4px;">Изменить статус:</label>
                    <select id="status" name="status" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Новый</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обработке</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Отправлен</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Доставлен</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Отменён</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">Обновить статус</button>
            </form>
            <hr style="border: none; border-top: 1px solid #f0e6d6; margin: 15px 0;">
            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Отменить заказ?');">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">Отменить заказ</button>
            </form>
        </div>
    </div>
</div>
@endsection
