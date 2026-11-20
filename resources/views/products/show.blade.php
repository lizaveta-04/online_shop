@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="product-detail">
    <div class="product-detail-image">
        <img src="{{ $product->image_url ?? asset('images/no-image.svg') }}" alt="{{ $product->name }}">
    </div>
    <div class="product-detail-info">
        <h1>{{ $product->name }}</h1>
        <p class="price">{{ number_format($product->price, 0, ',', ' ') }} ₽</p>

        @if($product->category)
            <p style="font-size: 13px; color: #7a6a58;">Категория: <strong>{{ $product->category->name }}</strong></p>
        @endif

        @if($product->description)
            <h3>Описание товара</h3>
            <p>{{ $product->description }}</p>
        @endif

        @if($product->is_active && $product->stock > 0)
            <div style="margin-top: 25px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <form action="{{ route('cart.add', $product->slug) }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
                    @csrf
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="qty" style="font-weight: bold;">Кол-во:</label>
                        <input type="number" id="qty" name="quantity" class="cart-qty" value="1" min="1" max="{{ $product->stock }}">
                    </div>
                    <button type="submit" class="btn btn-primary">🛒 В корзину</button>
                </form>
            </div>
            <p style="font-size: 13px; color: #2e7d32; margin-top: 10px;">✔ В наличии: {{ $product->stock }} шт.</p>
        @elseif($product->stock <= 0)
            <p style="color: #c62828; font-weight: bold; margin-top: 15px;">✕ Нет в наличии</p>
        @endif

        <a href="{{ route('catalog.index') }}" class="back-button" style="margin-top: 15px;">← Вернуться в каталог</a>
    </div>
</div>
@endsection
