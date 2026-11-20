@extends('layouts.admin')

@section('title', $product ? 'Редактировать товар' : 'Добавить товар')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li><a href="{{ route('admin.products.index') }}">Товары</a></li>
    <li>{{ $product ? 'Редактировать' : 'Добавить товар' }}</li>
</ol>

<div class="admin-header">
    <h1>{{ $product ? 'Редактирование товара' : 'Добавление товара' }}</h1>
</div>

<div class="card" style="max-width: 650px;">
    <div class="card-body">
        <form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($product) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Название товара:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="Например: Корм для собак">
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="4" placeholder="Подробное описание товара...">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="price">Цена (₽):</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0" step="0.01" placeholder="450.00">
                </div>
                <div class="form-group">
                    <label for="stock">Остаток на складе:</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}" required min="0" placeholder="100">
                </div>
            </div>

            <div class="form-group">
                <label for="category_id">Категория:</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image">Изображение:</label>
                @if($product)
                    <br><img src="{{ $product->image_url ?? asset('images/no-image.svg') }}" class="image-preview" alt="">
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color: #7a6a58; font-size: 12px;">PNG, JPG до 2 МБ</small>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}> Опубликовать товар
                </label>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary">{{ $product ? 'Сохранить' : 'Создать товар' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection
