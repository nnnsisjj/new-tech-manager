@extends('admin.layouts.app')

@section('title', 'Управление преподавателями')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Список преподавателей</h1>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить преподавателя
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Специализация</th>
                            <th>Email</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>{{ $teacher->full_name }}</td>
                            <td>{{ $teacher->specialization }}</td>
                            <td>{{ $teacher->user->email }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" 
                                   class="btn btn-sm btn-primary mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Удалить этого преподавателя?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Нет преподавателей</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection