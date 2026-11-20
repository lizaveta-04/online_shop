@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="content">
    <div class="left">
        <h1>Домашние животные</h1>
        <div class="info-box">
            <p class="lead">🐾 Найди своего верного друга!</p>
            <p>Мы поможем:<br>
                - подобрать идеального питомца<br>
                - связаться с текущими хозяевами или приютами<br>
                - приобрести качественные товары для вашего питомца
            </p>
        </div>
        <a href="{{ route('applications.create') }}" class="cta">Успейте оставить заявку на усыновление питомца!</a>
    </div>
    <div class="right">
        <img src="{{ asset('images/dog.jpg') }}" alt="Собака" onerror="this.style.display='none'">
    </div>
</div>
@endsection
