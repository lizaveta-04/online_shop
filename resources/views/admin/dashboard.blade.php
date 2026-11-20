@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li>Дашборд</li>
</ol>

<div class="admin-header">
    <h1>Обзор</h1>
    <div class="admin-user">
        <span>{{ auth()->user()->name }}</span>
        <div class="admin-user-icon">👤</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div>
            <div class="stat-value">{{ $stats['products'] }}</div>
            <div class="stat-label">Товаров</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📁</div>
        <div>
            <div class="stat-value">{{ $stats['categories'] }}</div>
            <div class="stat-label">Категорий</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div>
            <div class="stat-value">{{ $stats['orders'] }}</div>
            <div class="stat-label">Заказов</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div>
            <div class="stat-value">{{ $stats['applications'] }}</div>
            <div class="stat-label">Новых заявок</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div>
            <div class="stat-value">{{ $stats['users'] }}</div>
            <div class="stat-label">Пользователей</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div>
            <div class="stat-value">{{ number_format($stats['revenue'], 0, ',', ' ') }} ₽</div>
            <div class="stat-label">Выручка</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px;">
    <div class="card">
        <div class="card-header">Последние заказы</div>
        <div class="card-body" style="padding: 0;">
            @if($recentOrders->isEmpty())
                <p style="padding: 20px; color: #7a6a58; text-align: center;">Заказов пока нет.</p>
            @else
                <table class="table" style="border-radius: 0;">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Пользователь</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" style="color: #d86800;">#{{ $order->id }}</a></td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">Новые заявки</div>
        <div class="card-body" style="padding: 0;">
            @if($newApplications->isEmpty())
                <p style="padding: 20px; color: #7a6a58; text-align: center;">Новых заявок нет.</p>
            @else
                <table class="table" style="border-radius: 0;">
                    <thead>
                        <tr>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newApplications as $app)
                        <tr>
                            <td><a href="{{ route('admin.applications.show', $app) }}" style="color: #d86800;">{{ $app->fullname }}</a></td>
                            <td>{{ $app->phone }}</td>
                            <td><span class="badge badge-{{ $app->status }}">Новая</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
