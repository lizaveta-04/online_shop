@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h1>Вход в аккаунт</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="example@mail.ru">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required placeholder="Введите пароль">
            </div>
            <div class="form-group">
                <label style="font-weight: normal;">
                    <input type="checkbox" name="remember" value="1"> Запомнить меня
                </label>
            </div>
            <button type="submit" class="btn btn-primary submit-btn">Войти</button>
        </form>
        <div class="auth-links">
            <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
        </div>
    </div>
</div>
@endsection
