@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li><a href="{{ route('cart.index') }}">Корзина</a></li>
    <li>Оформление</li>
</ol>

<h1 style="color: #d86800; margin: 10px 0 25px;">Оформление заказа</h1>

<div class="checkout-layout">
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">📍 Адрес доставки</div>
            <div class="card-body">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="address">Адрес доставки:</label>
                        <textarea id="address" name="address" class="form-control" rows="3" required placeholder="Город, улица, дом, квартира">{{ old('address') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="comment">Комментарий к заказу:</label>
                        <textarea id="comment" name="comment" class="form-control" rows="3" placeholder="Пожелания к доставке, время и т.д.">{{ old('comment') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary submit-btn">Подтвердить заказ</button>
                </form>
            </div>
        </div>
    </div>

    <aside>
        <div class="card">
            <div class="card-header">📦 Ваш заказ</div>
            <div class="card-body">
                @foreach($items as $item)
                <div class="checkout-order-item">
                    <div class="checkout-item-info">
                        <div class="checkout-item-name">{{ $item['product']->name }}</div>
                        <div class="checkout-item-detail">{{ number_format($item['product']->price, 0, ',', ' ') }} ₽ × {{ $item['quantity'] }}</div>
                    </div>
                    <strong>{{ number_format($item['subtotal'], 0, ',', ' ') }} ₽</strong>
                </div>
                @endforeach

                <div class="summary-row" style="margin-top: 15px;">
                    <span>Товары</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>
                <div class="summary-row">
                    <span>Доставка</span>
                    <span>Бесплатно</span>
                </div>
                <div class="summary-total">
                    <span>Итого</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>

                <a href="{{ route('cart.index') }}" class="btn btn-outline" style="width: 100%; margin-top: 15px; text-align: center;">Вернуться в корзину</a>
            </div>
        </div>
    </aside>
</div>
@endsection
