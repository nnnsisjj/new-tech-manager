@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Панель управления</h1>
    </div>

    <div class="row">
        <!-- Статистика студентов -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Всего студентов</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика курсов -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Активных курсов</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coursesCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ближайшие занятия -->
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ближайшие занятия</h6>
                </div>
                <div class="card-body">
                    @if($upcomingClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Курс</th>
                                        <th>Преподаватель</th>
                                        <th>Дата и время</th>
                                        <th>Аудитория</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingClasses as $class)
                                    <tr>
                                        <td>{{ $class->course->name }}</td>
                                        <td>{{ $class->teacher->full_name }}</td>
                                        <td>{{ $class->start_time->format('d.m.Y H:i') }}</td>
                                        <td>{{ $class->classroom }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Нет предстоящих занятий</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection