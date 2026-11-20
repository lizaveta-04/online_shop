@extends('layouts.admin')

@section('title', 'Категории')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li>Категории</li>
</ol>

<div class="admin-header">
    <h1>Управление категориями</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Добавить категорию</a>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Название</th>
                <th>Slug</th>
                <th>Кол-во товаров</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr style="{{ $cat->trashed() ? 'opacity: 0.5;' : '' }}">
                <td>{{ $cat->id }}</td>
                <td><strong>{{ $cat->name }}</strong></td>
                <td><code>{{ $cat->slug }}</code></td>
                <td>{{ $cat->products_count }}</td>
                <td>
                    @if($cat->trashed())
                        <span class="badge badge-cancelled">Удалена</span>
                    @else
                        <span class="badge badge-active">Активна</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-secondary">✏️</a>
                        @if(!$cat->trashed())
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Удалить категорию?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; padding: 40px;">Категории не найдены.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $categories->appends(request()->query())->links() }}
@endsection
