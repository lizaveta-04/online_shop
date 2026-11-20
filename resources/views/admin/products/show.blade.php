@extends('layouts.admin')

@section('title', 'Товар: ' . $product->name)

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li><a href="{{ route('admin.products.index') }}">Товары</a></li>
    <li>{{ $product->name }}</li>
</ol>

<div class="admin-header">
    <h1>{{ $product->name }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">✏️ Редактировать</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">← К списку</a>
    </div>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <div class="profile-info-grid">
            <div class="profile-info-item">
                <label>ID</label>
                <div class="value">{{ $product->id }}</div>
            </div>
            <div class="profile-info-item">
                <label>Название</label>
                <div class="value">{{ $product->name }}</div>
            </div>
            <div class="profile-info-item">
                <label>Категория</label>
                <div class="value">{{ $product->category->name ?? '—' }}</div>
            </div>
            <div class="profile-info-item">
                <label>Цена</label>
                <div class="value">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
            </div>
            <div class="profile-info-item">
                <label>Остаток</label>
                <div class="value">{{ $product->stock }}</div>
            </div>
            <div class="profile-info-item">
                <label>Статус</label>
                <div class="value">
                    @if($product->is_active)
                        <span class="badge badge-active">Активен</span>
                    @else
                        <span class="badge badge-hidden">Скрыт</span>
                    @endif
                    @if($product->trashed()) / <span class="badge badge-cancelled">Удалён</span> @endif
                </div>
            </div>
        </div>

        @if($product->description)
        <hr style="border: none; border-top: 1px solid #f0e6d6; margin: 20px 0;">
        <h3 style="color: #d86800; margin: 0 0 10px;">Описание</h3>
        <p>{{ $product->description }}</p>
        @endif
    </div>
</div>
@endsection
