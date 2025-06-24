@extends('admin.layouts.app')

@section('title', 'Личный кабинет студента')

@section('content')
<div class="container-fluid">
    @auth
    <h1 class="mb-4">Добро пожаловать, {{ auth()->user()->student->full_name ?? auth()->user()->name }}!</h1>
    
    @if(auth()->user()->student)
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Мой профиль</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ФИО:</strong> {{ auth()->user()->student->full_name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->student->email ?? 'Не указан' }}</p>
                                <p><strong>Телефон:</strong> {{ auth()->user()->student->phone ?? 'Не указан' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Курс:</strong> 
                                    @if($course = App\Models\Course::find(auth()->user()->student->course_id))
                                        {{ $course->name }}
                                    @else
                                        Не указан
                                    @endif
                                </p>
                                <p><strong>Группа:</strong> 
                                    @if($group = App\Models\Group::find(auth()->user()->student->group_id))
                                        {{ $group->name }}
                                    @else
                                        Не указана
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('student.profile') }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Редактировать профиль
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Профиль студента не найден. Обратитесь к администратору.
        </div>
    @endif
    @else
    <div class="alert alert-warning">
        Пожалуйста, войдите в систему.
    </div>
    @endauth
</div>
@endsection