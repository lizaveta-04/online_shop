@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
<h1 class="catalog-title">Каталог товаров</h1>

<form method="GET" action="{{ route('catalog.index') }}" class="search-box">
    <input type="text" name="search" placeholder="🔍 Поиск товаров" value="{{ request('search') }}">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <button type="submit">Найти</button>
</form>

@if(request('search') || request('category'))
    <p style="font-size: 14px; color: #7a6a58; margin-bottom: 15px;">
        <a href="{{ route('catalog.index') }}" style="color: #d86800;">✕ Сбросить фильтры</a>
    </p>
@endif

<div class="catalog-grid">
    @forelse($products as $product)
        <a href="{{ route('products.show', $product->slug) }}" class="product-link">
            <div class="product">
                <img src="{{ $product->image_url ?? asset('images/no-image.svg') }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}<br><strong>{{ number_format($product->price, 0, ',', ' ') }} ₽</strong></p>
            </div>
        </a>
    @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <div class="empty-icon">🔍</div>
            <p>Товары не найдены.</p>
        </div>
    @endforelse
</div>

{{ $products->appends(request()->query())->links() }}
@endsection
