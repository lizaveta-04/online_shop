@extends('layouts.admin')

@section('title', 'Товары')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li>Товары</li>
</ol>

<div class="admin-header">
    <h1>Управление товарами</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Добавить товар</a>
</div>

<form method="GET" action="{{ route('admin.products.index') }}" class="filters">
    <div class="form-group">
        <input type="text" name="search" placeholder="🔍 Поиск по названию..." value="{{ request('search') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
    </div>
    <div class="form-group">
        <select name="category" class="form-select">
            <option value="">Все категории</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <select name="active" class="form-select">
            <option value="">Все статусы</option>
            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Активный</option>
            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Скрыт</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height: 42px;">Найти</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Остаток</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="{{ $product->trashed() ? 'opacity: 0.5;' : '' }}">
                <td>{{ $product->id }}</td>
                <td><img src="{{ $product->image_url ?? asset('images/no-image.svg') }}" alt="" class="thumbnail"></td>
                <td>
                    <strong>{{ $product->name }}</strong>
                    @if($product->trashed())<br><small style="color: #c62828;">(удалён)</small>@endif
                </td>
                <td>{{ $product->category->name ?? '—' }}</td>
                <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if($product->trashed())
                        <span class="badge badge-cancelled">Удалён</span>
                    @elseif($product->is_active)
                        <span class="badge badge-active">Активен</span>
                    @else
                        <span class="badge badge-hidden">Скрыт</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-primary">👁</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary">✏️</a>
                        @if(!$product->trashed())
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Удалить товар?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">Товары не найдены.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $products->appends(request()->query())->links() }}
@endsection
