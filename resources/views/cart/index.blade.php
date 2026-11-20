@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li><a href="{{ route('catalog.index') }}">Каталог</a></li>
    <li>Корзина</li>
</ol>

<h1 style="color: #d86800; margin: 10px 0 25px;">Корзина</h1>

@if(empty($items))
    <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <p>Корзина пуста.</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
    </div>
@else
    <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-start;">
        <div style="flex: 1; min-width: 0;">
            <div class="table-wrapper">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Кол-во</th>
                            <th>Сумма</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="product-name-cell">
                                    <span>{{ $item['product']->name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item['product']->price, 0, ',', ' ') }} ₽</td>
                            <td>
                                <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="number" name="quantity" class="cart-qty" value="{{ $item['quantity'] }}" min="1" onchange="this.form.submit();">
                                </form>
                            </td>
                            <td><strong>{{ number_format($item['subtotal'], 0, ',', ' ') }} ₽</strong></td>
                            <td>
                                <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="cart-remove" title="Удалить">✕</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline" style="margin-top: 15px;">← Продолжить покупки</a>
        </div>

        <aside class="cart-summary">
            <h3 style="margin: 0 0 15px; color: #d86800; font-size: 18px;">Итого</h3>
            <div class="summary-row">
                <span>Товары ({{ collect($items)->sum('quantity') }} шт.)</span>
                <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
            </div>
            <div class="summary-row">
                <span>Доставка</span>
                <span>Бесплатно</span>
            </div>
            <div class="summary-total">
                <span>К оплате</span>
                <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
            </div>
            <a href="{{ route('checkout.create') }}" class="btn btn-primary submit-btn" style="width: 100%; margin-top: 20px; text-align: center;">Оформить заказ</a>
        </aside>
    </div>
@endif
@endsection
