<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Домашние животные')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="page-wrapper">
        <header>
            <nav class="nav">
                <div class="container">
                    <a href="{{ route('home') }}" class="logo" style="text-decoration: none;">
                        <img src="{{ asset('images/logo.png') }}" alt="Лапка" class="logo-paw"> Домашние животные
                    </a>
                    <div class="menu">
                        <a href="{{ route('home') }}">Главная</a>
                        <a href="{{ route('catalog.index') }}">Каталог</a>
                        <a href="{{ route('applications.create') }}">Оставить заявку</a>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">Админ-панель</a>
                            @endif
                            <a href="{{ route('cart.index') }}">🛒 Корзина</a>
                            <a href="{{ route('profile.show') }}">👤 {{ auth()->user()->name }}</a>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="btn-logout">Выйти</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}">Вход</a>
                            <a href="{{ route('register') }}">Регистрация</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer>
            <div class="container">
                <div class="contacts">
                    Контакты<br>
                    Телефон: 89278005954<br>
                    email: nastyasadh@gmail.com
                </div>
                <div class="copyright">
                    ©{{ date('Y') }} Домашние животные.<br>
                    Все права защищены.
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>

</html>
