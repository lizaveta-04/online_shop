# Онлайн-магазин «Домашние животные» — презентация проекта

## Тема: Интернет-магазин (вариант 3)
**Фреймворк:** Laravel 13.31 | **PHP:** 8.5.12 | **БД:** MySQL (utf8mb4)

---

## 1. Что реализовано по этапам задания

### Этап 1. Проектирование

#### 1.1 Таблицы БД (6 таблиц)

| Таблица | Назначение | Ключевые поля |
|---|---|---|
| `users` | Пользователи и администраторы | id, name, email, password, **role** ENUM('user','admin'), phone |
| `categories` | Категории товаров | id, name, slug (unique), description, deleted_at |
| `products` | Товары | id, category_id FK, name, slug (unique), price DECIMAL(10,2), stock, image, is_active, deleted_at |
| `orders` | Заказы | id, user_id FK, total_price, status ENUM(5 значений), address, comment |
| `order_items` | Позиции заказа | id, order_id FK, product_id FK, quantity, price |
| `applications` | Заявки на питомца | id, fullname, phone, email, wishes, status ENUM(4 значения) |

#### 1.2 Связи между таблицами

```
categories 1 ───< N products 1 ───< N order_items >─── 1 orders >─── 1 users
```

- Категория → товары: один-ко-многим (`category_id`, `nullOnDelete`)
- Пользователь → заказы: один-ко-многим (`user_id`, `cascadeOnDelete`)
- Заказ → позиции: один-ко-многим (`order_id`, `cascadeOnDelete`)
- Товар → позиции: один-ко-многим (`product_id`, `cascadeOnDelete`)
- Заявки: без связей с другими таблицами

ER-диаграмма: `docs/photo/er-dia.png`

#### 1.3 Карта экранов

**Публичные страницы (гость):**

| URL | Описание |
|---|---|
| `/` | Главная страница |
| `/catalog` | Каталог товаров с поиском и фильтром по категориям |
| `/products/{slug}` | Карточка товара |
| `/applications/create` | Форма заявки на питомца |
| `/login` | Вход |
| `/register` | Регистрация |
| `/password/reset` | Восстановление пароля |

**Страницы авторизованного пользователя:**

| URL | Описание |
|---|---|
| `/cart` | Корзина |
| `/checkout` | Оформление заказа (адрес + комментарий) |
| `/profile` | Личный кабинет (редактирование профиля) |
| `/profile/orders` | История заказов |
| `/profile/orders/{id}` | Детали заказа |

**Админ-панель (`/admin`, доступ только для role=admin):**

| URL | Описание |
|---|---|
| `/admin/dashboard` | Дашборд со статистикой |
| `/admin/products` | Список товаров (CRUD) |
| `/admin/categories` | Список категорий (CRUD) |
| `/admin/orders` | Управление заказами (смена статуса, отмена) |
| `/admin/applications` | Модерирование заявок |

Всего в проекте **47 маршрутов**.

#### 1.4 Роли пользователей и сценарии

| Роль | Права |
|---|---|
| **Гость** | Просмотр каталога, поиск, оставление заявки, регистрация |
| **User** | Всё выше + корзина, оформление заказа, личный кабинет, история заказов |
| **Admin** | Всё выше + управление товарами, категориями, заказами, заявками; дашборд |

Сценарии поведения описаны в README.md, раздел 1.4.

#### 1.5 Формы (7 основных)

1. Заявка на питомца — ФИО, телефон, email, пожелания
2. Регистрация — ФИО, email, телефон, пароль + подтверждение
3. Авторизация — email, пароль, «Запомнить меня»
4. Оформление заказа — адрес доставки, комментарий
5. Редактирование профиля — ФИО, телефон
6. Админ: товар — название, описание, цена, остаток, категория, изображение, публикация
7. Админ: категория — название, slug, описание

---

### Этапы 2–6. Окружение, БД, миграции

| Шаг | Статус |
|---|---|
| XAMPP, PHP 8.5.12, Composer (`composer.phar` локально) | ✅ |
| MySQL через phpMyAdmin, БД `online_shop` (utf8mb4) | ✅ |
| Виртуальный хост `online_shop.local` → `public/index.php` | ✅ |
| `.env` настроен (APP_KEY, DB_*) | ✅ |
| 9 миграций выполнены (`php artisan migrate`) | ✅ |
| Данные: сидеры `UserSeeder`, `CategorySeeder`, `ProductSeeder`; демо: 2 пользователя, 6 категорий, 10 товаров | ✅ |

---

### Этап 7. Контроллер ресурса

Для CRUD товаров и категорий в админке используются **ресурсные маршруты**:

```php
// routes/admin.php
Route::resource('products', ProductController::class)->withTrashed(['show', 'edit', 'update']);
Route::resource('categories', CategoryController::class)->except(['show'])->withTrashed(['edit', 'update']);
```

Контроллер содержит все 7 действий ресурса: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.

---

### Этап 8. Blade-шаблоны, Laravel UI, авторизация

**Laravel UI установлен** (`laravel/ui` ^4.6):

```sh
composer require laravel/ui
php artisan ui bootstrap --auth
```

Сгенерированы:
- Контроллеры: `Auth\LoginController`, `Auth\RegisterController`, `Auth\ForgotPasswordController`, `Auth\ResetPasswordController`, `Auth\ConfirmPasswordController`
- Маршруты авторизации: `Auth::routes()` в `routes/web.php`
- Шаблоны: `resources/views/auth/login.blade.php`, `register.blade.php`, `passwords/*`

Авторизация включает: вход, регистрацию, выход, восстановление пароля.

**Blade-шаблоны** (27 файлов):
- `layouts/app.blade.php` — основной макет (шапка, навигация, футер, сообщения об ошибках)
- `layouts/admin.blade.php` — макет админки (сайдбар, breadcrumbs)
- `home/`, `catalog/`, `products/`, `cart/`, `checkout/`, `profile/`, `applications/` — публичные страницы
- `admin/dashboard/`, `admin/products/`, `admin/categories/`, `admin/orders/`, `admin/applications/` — админка

Все шаблоны наследуются от макета через `@extends('layouts.app')` и используют `@section('content')` / `@yield('content')`.

---

### Этап 9. Маршрутизация

**routes/web.php** — публичные и пользовательские маршруты:
- GET `/` → главная
- GET `/catalog` → список товаров с фильтрацией
- GET `/products/{slug}` → карточка товара
- POST `/applications` → сохранение заявки
- `Auth::routes()` → вся авторизация
- Группа `middleware('auth')`: корзина, checkout, профиль

**routes/admin.php** — маршруты админ-панели:
- Группа `middleware(['auth', 'admin'])` с префиксом `admin`
- Ресурсные контроллеры для товаров и категорий
- REST-маршруты для управления заказами и заявками

**bootstrap/app.php** — подключение admin-routes:
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    then: function () {
        Route::middleware('web')->group(base_path('routes/admin.php'));
    }
)
```

---

### Этап 10. Библиотека Laravel-admin

Оригинальный пакет **`encore/laravel-admin` несовместим с Laravel 13**:
- Требует `doctrine/dbal 2.*` (актуальная версия — 4.x)
- Заблокирован Composer из-за security advisories

**Решение:** используется актуальный форк **`php-panel/laravel-admin` v3.1.0**, который поддерживает `laravel/framework ^12.0|^13.0` и `PHP >= 8.2`.

Установка (команды для запуска):
```powershell
php composer.phar require php-panel/laravel-admin
php artisan vendor:publish --provider="Ladmin\AdminServiceProvider"
php artisan admin:migrate
php artisan admin:make-admin admin@petshop.ru
```

После установки будут созданы:
- `config/admin.php` — конфигурация
- `app/Admin/Controllers/` — контроллеры панели администратора
- `routes/app/Admin/routes.php` — маршруты
- `public/vendor/laravel-admin/` — стили и скрипты

Пример контроллера (методы `Grid`, `Detail`, `Form`):
```php
// app/Admin/Controllers/ProductController.php
namespace App\Admin\Controllers;

use Ladmin\Controllers\Controller;
use Ladmin\Facades\Admin;
use App\Models\Shop\Product;

class ProductController extends Controller
{
    public function grid()
    {
        return Admin::grid(Product::class, function ($grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('name', 'Название');
            $grid->column('price', 'Цена');
            $grid->column('stock', 'Остаток');
            $grid->column('is_active', 'Активен')->using([1 => 'Да', 0 => 'Нет']);
        });
    }

    public function form()
    {
        return Admin::form(Product::class, function ($form) {
            $form->text('name', 'Название')->rules('required');
            $form->textarea('description', 'Описание');
            $form->number('price', 'Цена')->rules('required|min:0');
            $form->number('stock', 'Остаток')->rules('required|min:0');
            $form->select('category_id', 'Категория')->options(Category::pluck('name', 'id'));
            $form->image('image', 'Изображение');
            $form->switch('is_active', 'Публикация');
        });
    }
}
```

---

## 2. Архитектура MVC

### Модель (Model) — `app/Models/`

**Главная модель:**
- `User.php` — пользователь (аутентификация, роли, связь с заказами)

**Модели предметной области** — `app/Models/Shop/`:
- `Product.php` — товар (SoftDeletes, связи, accessor `image_url`)
- `Category.php` — категория (SoftDeletes,hasMany Products)
- `Order.php` — заказ (belongsTo User, hasMany OrderItems)
- `OrderItem.php` — позиция заказа (belongsTo Order, Product)
- `Application.php` — заявка

Все модели содержат DocBlock-аннотации (`@property`) для автодополнения IDE.

### Контроллер (Controller) — `app/Http/Controllers/`

**Базовый класс:**
- `Controller.php` — расширяет Laravel Routing Controller
- **`BaseController.php`** — абстрактный класс-наследник для всех контроллеров приложения (ООП, тема 4 методички)

**Публичные контроллеры:**
- `ProductController` — index (каталог), show (карточка)
- `CartController` — корзина (сессия)
- `CheckoutController` — оформление заказа (транзакция БД)
- `ApplicationController` — приём заявок
- `ProfileController` — личный кабинет

**Auth-контроллеры** (Laravel UI):
- `Auth/LoginController` — вход + редирект админа на dashboard
- `Auth/RegisterController` — регистрация + поле phone
- `Auth/ForgotPasswordController` — восстановление пароля

**Admin-контроллеры** — `Admin/`:
- `DashboardController` — статистика (кол-во товаров, заказов, выручка)
- `ProductController` — CRUD товаров + уникальность slug
- `CategoryController` — CRUD категорий
- `OrderController` — смена статусов, отмена
- `ApplicationController` — модерация заявок

### View (представления) — `resources/views/`

- `layouts/app.blade.php` — базовый шаблон (навигация, flash-сообщения, футер)
- `layouts/admin.blade.php` — шаблон админки (сайдбар, breadcrumbs)
- `home/`, `catalog/`, `products/`, `cart/`, `checkout/`, `profile/`, `applications/`
- `admin/products/`, `admin/categories/`, `admin/orders/`, `admin/applications/`, `admin/dashboard/`
- `auth/login.blade.php`, `auth/register.blade.php`, `auth/passwords/*`

---

## 3. Middleware и защита ролей

| Middleware | Назначение |
|---|---|
| `auth` | Требует авторизацию (корзина, checkout, профиль, вся админка) |
| `admin` (кастомный `AdminMiddleware`) | Проверяет `Auth::user()->isAdmin()` — доступ только при role='admin' |

Зарегистрирован в `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
]);
```

---

## 4. Особенности реализации

### 4.1 Корзина
Хранится в **сессии** (`session('cart')`), массив вида `[product_id => ['quantity' => N]]`. Не требует отдельной таблицы в БД.

### 4.2 Оформление заказа
- Все действия (создание заказа, позиций, списание остатков) выполняются внутри **`DB::transaction()`** — данные не потеряются при ошибке.
- Проверяется наличие товара и достаточность остатка.
- При удалённом товаре — безопасное сообщение, а не exception.

### 4.3 Slug товаров
- Генерируется автоматически из названия через `Str::slug($name)`.
- Проверяется уникальность с учётом мягко удалённых записей.
- При изменении названия в админке slug пересоздаётся; если название не менялось — остаётся прежним (не ломаются внешние ссылки).

### 4.4 Soft Deletes
Товары и категории используют trait `SoftDeletes` (`deleted_at`). В админке удалённые записи видны в списках (`withTrashed()`), но скрыты из каталога.

### 4.5 Изображения
- Актуальные загруженные через админку хранятся на диске `public` (`storage/app/public/products/`).
- Accessor `image_url` проверяет: внешний URL → файл в `public/` → файл в storage → null (placeholder).

### 4.6 Локализация
Русские переводы добавлены:
- `lang/ru/validation.php` — сообщения валидации
- `lang/ru/auth.php` — сообщения авторизации
- `lang/ru/pagination.php` — «Назад / Вперёд»
- `lang/ru.json` — строки интерфейса Bootstrap

### 4.7 Тестирование
Feature-тесты в `tests/Feature/ShopTest.php` (PHPUnit 12):
- 15 тестов, 56 проверок
- Покрытие: просмотр каталога, доступ неактивного товара, регистрация, вход, редирект админа, права доступа, CRUD товаров, уникальность slug, soft delete, checkout со списанием остатка, обработка удалённого товара, заявки, смена статусов заказа и заявки, защита чужих заказов

Запуск:
```powershell
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
```

---

## 5. Структура проекта

```
online_shop.local/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BaseController.php       ← абстрактный (тема 4)
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── ApplicationController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── Auth/                    ← Laravel UI
│   │   │   └── Admin/                   ← админ-панель
│   │   └── Middleware/
│   │       └── AdminMiddleware.php      ← проверка role=admin
│   └── Models/
│       ├── User.php
│       └── Shop/                        ← доменные модели (тема 6)
│           ├── Product.php
│           ├── Category.php
│           ├── Order.php
│           ├── OrderItem.php
│           └── Application.php
├── database/
│   ├── migrations/                      ← 9 файлов
│   ├── seeders/                         ← 3 сидера
│   └── factories/
├── lang/ru/                             ← переводы
├── public/
│   ├── css/styles.css                   ← стили
│   ├── js/script.js                     ← JS
│   ├── images/                          ← изображения
│   └── index.php                        ← точка входа
├── resources/views/                     ← 27 Blade-шаблонов
├── routes/
│   ├── web.php                          ← публичные маршруты
│   └── admin.php                        ← маршруты админки
├── tests/Feature/ShopTest.php           ← 15 feature-тестов
├── .env                                 ← локальные настройки
├── composer.json                        ← зависимости PHP
├── package.json                         ← зависимости Node
└── README.md                            ← полная документация
```

---

## 6. Как запустить проект

```powershell
cd d:\xampp\htdocs\laravel\online_shop.local
php composer.phar install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Открыть в браузере: http://localhost:8000

Тестовые учётные записи:

| Роль | Email | Пароль |
|---|---|---|
| Администратор | `admin@petshop.ru` | `admin12345` |
| Покупатель | `user@petshop.ru` | `user12345` |

---

## 7. Соответствие требованиям практического задания

| Требование | Реализация |
|---|---|
| а) Анализ и структура приложения | README.md разделы 1.1–1.8, ER-диаграмма, карта экранов, сценарии, формы |
| б) Функционал администратора (добавление, удаление, просмотр, модерация, обработка заказа) | Полноценная админ-панель: CRUD товаров/категорий, смена статусов заказов, модерация заявок, дашборд |
| в) Вывод данных пользователю + оформление заказа | Каталог, карточка товара, корзина, checkout, профиль, история заказов, форма заявки |
| Laravel 10+ | Laravel 13.31 |
