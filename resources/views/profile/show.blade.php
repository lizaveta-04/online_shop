@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('home') }}">Главная</a></li>
    <li>Личный кабинет</li>
</ol>

<div class="profile-layout">
    <aside class="profile-nav">
        <div class="profile-nav-title">Личный кабинет</div>
        <a href="{{ route('profile.show') }}" class="profile-nav-link active">👤 Профиль</a>
        <a href="{{ route('profile.orders') }}" class="profile-nav-link">📦 Мои заказы</a>
        <a href="{{ route('applications.create') }}" class="profile-nav-link">📝 Заявки</a>
    </aside>

    <div class="profile-main">
        <h1 style="color: #d86800; margin: 0 0 20px;">Мой профиль</h1>

        <div class="profile-info-grid">
            <div class="profile-info-item">
                <label>ФИО</label>
                <div class="value">{{ $user->name }}</div>
            </div>
            <div class="profile-info-item">
                <label>Email</label>
                <div class="value">{{ $user->email }}</div>
            </div>
            <div class="profile-info-item">
                <label>Телефон</label>
                <div class="value">{{ $user->phone ?? 'Не указан' }}</div>
            </div>
            <div class="profile-info-item">
                <label>Дата регистрации</label>
                <div class="value">{{ $user->created_at->format('d.m.Y') }}</div>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #f0e6d6; margin: 25px 0;">

        <h2 style="color: #d86800; font-size: 18px; margin: 0 0 15px;">Редактировать профиль</h2>
        <form action="{{ route('profile.update') }}" method="POST" style="max-width: 500px;">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit-name">ФИО:</label>
                <input type="text" id="edit-name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label for="edit-phone">Телефон:</label>
                <input type="tel" id="edit-phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+7 (999) 123-45-67">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
</div>
@endsection
