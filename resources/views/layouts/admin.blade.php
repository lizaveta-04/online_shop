<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель') — Домашние животные</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="page-wrapper">
        <header>
            <nav class="nav">
                <div class="container">
                    <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Лапка" class="logo-paw"> Админ-панель</div>
                    <div class="menu">
                        <a href="{{ route('home') }}" target="_blank">Магазин</a>
                        <a href="{{ route('admin.dashboard') }}">Дашборд</a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #d86800; font-weight: bold; cursor: pointer; font-size: 14px; padding: 8px 12px;">Выйти</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>

        <div class="admin-layout">
            <aside class="sidebar">
                <div class="sidebar-title"><img src="{{ asset('images/logo.png') }}" alt="Лапка" class="logo-paw"> Админ-панель</div>
                <div class="sidebar-section-title">Основное</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">📊</span> Дашборд
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section-title">Каталог</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">📦</span> Товары
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">📁</span> Категории
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section-title">Продажи</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">🛒</span> Заказы
                        </a>
                    </li>
                </ul>
                <div class="sidebar-section-title">Обратная связь</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="{{ route('admin.applications.index') }}" class="sidebar-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">📝</span> Заявки
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="admin-content">
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
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>

</html>
