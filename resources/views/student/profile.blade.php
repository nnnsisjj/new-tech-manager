@extends('admin.layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Редактирование профиля</h1>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
    
    <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">ФИО *</label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                   value="{{ old('full_name', $student->full_name) }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $student->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Группа</label>
                            <select name="group_id" class="form-control @error('group_id') is-invalid @enderror">
                                <option value="">-- Выберите группу --</option>
                                @foreach(App\Models\Group::all() as $group)
                                    <option value="{{ $group->id }}" 
                                        @selected(old('group_id', $student->group_id) == $group->id)>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Курс *</label>
                    <select name="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                        <option value="">-- Выберите курс --</option>
                        @foreach(App\Models\Course::all() as $course)
                            <option value="{{ $course->id }}" 
                                @selected(old('course_id', $student->course_id) == $course->id)>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection