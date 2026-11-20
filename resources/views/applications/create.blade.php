@extends('layouts.app')

@section('title', 'Оставить заявку')

@section('content')
<h1 class="form-title">Заявка на питомца</h1>

<form class="pet-form" action="{{ route('applications.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="fullname">ФИО:</label>
        <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required placeholder="Иванов Иван Иванович">
    </div>

    <div class="form-group">
        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+7 (999) 123-45-67">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="example@mail.ru">
    </div>

    <div class="form-group">
        <label for="wishes">Ваши пожелания:</label>
        <textarea id="wishes" name="wishes" rows="4" placeholder="Опишите ваши пожелания к питомцу...">{{ old('wishes') }}</textarea>
    </div>

    <button type="submit" class="submit-btn">Отправить заявку</button>
</form>
@endsection
