@extends('layouts.admin')

@section('title', 'Заявка №' . $application->id)

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
    <li><a href="{{ route('admin.applications.index') }}">Заявки</a></li>
    <li>Заявка №{{ $application->id }}</li>
</ol>

<div class="admin-header">
    <div style="display: flex; align-items: center; gap: 15px;">
        <h1 style="margin: 0;">Заявка №{{ $application->id }}</h1>
        <span class="badge badge-{{ $application->status }}" style="font-size: 14px; padding: 6px 14px;">
            @switch($application->status)
                @case('new') Новая @break
                @case('in_progress') В работе @break
                @case('completed') Завершена @break
                @case('rejected') Отклонена @break
            @endswitch
        </span>
    </div>
    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline">← К списку</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">
    <div class="card">
        <div class="card-header">📋 Данные заявки</div>
        <div class="card-body">
            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <label>ФИО</label>
                    <div class="value">{{ $application->fullname }}</div>
                </div>
                <div class="profile-info-item">
                    <label>Телефон</label>
                    <div class="value">{{ $application->phone }}</div>
                </div>
                <div class="profile-info-item">
                    <label>Email</label>
                    <div class="value">{{ $application->email }}</div>
                </div>
                <div class="profile-info-item">
                    <label>Дата создания</label>
                    <div class="value">{{ $application->created_at->format('d.m.Y, H:i') }}</div>
                </div>
            </div>
            @if($application->wishes)
                <hr style="border: none; border-top: 1px solid #f0e6d6; margin: 20px 0;">
                <h3 style="color: #d86800; font-size: 16px; margin: 0 0 10px;">Пожелания</h3>
                <p style="line-height: 1.6; color: #333;">{{ $application->wishes }}</p>
            @endif
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <div class="card">
            <div class="card-header">⚙️ Действия</div>
            <div class="card-body">
                <form action="{{ route('admin.applications.status', $application) }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                    @csrf
                    @method('PATCH')
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="app-status" style="font-size: 13px; margin-bottom: 4px;">Изменить статус:</label>
                        <select id="app-status" name="status" class="form-select">
                            <option value="new" {{ $application->status === 'new' ? 'selected' : '' }}>Новая</option>
                            <option value="in_progress" {{ $application->status === 'in_progress' ? 'selected' : '' }}>В работе</option>
                            <option value="completed" {{ $application->status === 'completed' ? 'selected' : '' }}>Завершена</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Отклонена</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
