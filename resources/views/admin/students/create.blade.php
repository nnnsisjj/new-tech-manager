@extends('admin.layouts.app')

@section('title', 'Добавить студента')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить нового студента</h1>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">ФИО *</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                               id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
    <div class="col-md-6">
        <label for="username" class="form-label">Логин *</label>
        <input type="text" class="form-control @error('username') is-invalid @enderror" 
               id="username" name="username" value="{{ old('username') }}" required>
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <label for="course" class="form-label">Курс *</label>
    <select name="course" class="form-control @error('course') is-invalid @enderror" required>
        <option value="">-- Выберите курс --</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ old('course') == $course->id ? 'selected' : '' }}>
                {{ $course->name }} ({{ $course->level }} курс)
            </option>
        @endforeach
    </select>
    @error('course')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Пароль *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля *</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection