@extends('layouts.admin')

@section('title', 'Заявки')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li>Заявки</li>
</ol>

<div class="admin-header">
    <h1>Управление заявками</h1>
</div>

<form method="GET" action="{{ route('admin.applications.index') }}" class="filters">
    <div class="form-group">
        <input type="text" name="search" placeholder="🔍 Поиск по ФИО / email..." value="{{ request('search') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
    </div>
    <div class="form-group">
        <select name="status" class="form-select">
            <option value="">Все статусы</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Новая</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>В работе</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Завершена</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Отклонена</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height: 42px;">Найти</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
            <tr>
                <td>{{ $app->id }}</td>
                <td><strong>{{ $app->fullname }}</strong></td>
                <td>{{ $app->phone }}</td>
                <td>{{ $app->email }}</td>
                <td>
                    <span class="badge badge-{{ $app->status }}">
                        @switch($app->status)
                            @case('new') Новая @break
                            @case('in_progress') В работе @break
                            @case('completed') Завершена @break
                            @case('rejected') Отклонена @break
                        @endswitch
                    </span>
                </td>
                <td>{{ $app->created_at->format('d.m.Y') }}</td>
                <td><a href="{{ route('admin.applications.show', $app) }}" class="btn btn-sm btn-secondary">Подробнее</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 40px;">Заявки не найдены.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $applications->appends(request()->query())->links() }}
@endsection
