@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h1>Регистрация</h1>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">ФИО:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Иванов Иван Иванович">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="example@mail.ru">
            </div>
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required minlength="8" placeholder="Минимум 8 символов">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Повторите пароль">
            </div>
            <button type="submit" class="btn btn-primary submit-btn">Зарегистрироваться</button>
        </form>
        <div class="auth-links">
            <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
        </div>
    </div>
</div>
@endsection
