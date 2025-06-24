@extends('admin.layouts.app')

@section('title', 'Управление студентами')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Список студентов</h1>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить студента
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Курс</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->course_id }}</td>
                            <td>{{ $student->phone ?? '—' }}</td>
                            <td>{{ $student->user->email }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.students.edit', $student->id) }}" 
                                   class="btn btn-sm btn-primary mr-2" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Вы уверены, что хотите удалить этого студента?')"
                                        title="Удалить">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Нет студентов</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection