@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Админ-панель</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Студенты</h5>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-primary">
                        Управление
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Преподаватели</h5>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-primary">
                        Управление
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Расписание</h5>
                    <a href="{{ route('admin.schedule.index') }}" class="btn btn-primary">
                        Управление
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection