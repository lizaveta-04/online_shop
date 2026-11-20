@extends('layouts.admin')

@section('title', isset($category) ? 'Редактировать категорию' : 'Добавить категорию')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li><a href="{{ route('admin.categories.index') }}">Категории</a></li>
    <li>{{ isset($category) ? 'Редактировать' : 'Добавить' }}</li>
</ol>

<div class="admin-header">
    <h1>{{ isset($category) ? 'Редактирование категории' : 'Добавление категории' }}</h1>
</div>

<div class="card" style="max-width: 550px;">
    <div class="card-body">
        <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required placeholder="Название категории">
            </div>

            <div class="form-group">
                <label for="slug">Slug (URL):</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required placeholder="category-slug">
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="4" placeholder="Описание категории...">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Сохранить' : 'Создать категорию' }}</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection
