@extends('admin.layouts.app')

@section('title', 'Расписание занятий')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Расписание занятий</h6>
            <div>
                <a href="{{ route('admin.schedule.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Добавить
                </a>
                <a href="{{ route('admin.schedule.export', request()->query()) }}" 
                   class="btn btn-sm btn-success ml-2">
                    <i class="fas fa-file-excel"></i> Экспорт
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Фильтры -->
            <form method="GET" action="{{ route('admin.schedule.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label>Направление</label>
                        <select name="direction_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Все направления</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}" 
                                    {{ request('direction_id') == $direction->id ? 'selected' : '' }}>
                                    {{ $direction->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label>Группа</label>
                        <select name="group_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Все группы</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" 
                                    {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ $group->course_level }} курс)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label>Преподаватель</label>
                        <select name="teacher_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Все преподаватели</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" 
                                    {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary btn-block">
                            Сбросить фильтры
                        </a>
                    </div>
                </div>
            </form>

            <!-- Таблица расписания -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Группа</th>
                            <th>Направление</th>
                            <th>Предмет</th>
                            <th>Преподаватель</th>
                            <th>Дата и время</th>
                            <th>Аудитория</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->group->name ?? '—' }}</td>
                            <td>{{ $schedule->group->direction->name ?? '—' }}</td>
                            <td>{{ $schedule->subject->name ?? '—' }}</td>
                            <td>{{ $schedule->teacher->full_name ?? '—' }}</td>
                            <td>
    {{ \Carbon\Carbon::parse($schedule->start_time)->format('d.m.Y H:i') }} - 
    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
</td>
                            <td>{{ $schedule->classroom }}</td>
                            <td nowrap>
                                <a href="{{ route('admin.schedule.edit', $schedule->id) }}" 
                                   class="btn btn-sm btn-primary" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.schedule.destroy', $schedule->id) }}" 
                                      method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Удалить это занятие?')" title="Удалить">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Нет данных по заданным фильтрам</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            <div class="mt-3">
                {{ $schedules->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Динамическая загрузка групп при выборе направления
    document.querySelector('select[name="direction_id"]').addEventListener('change', function() {
        const directionId = this.value;
        const groupSelect = document.querySelector('select[name="group_id"]');
        
        if (directionId) {
            fetch(`/api/groups?direction_id=${directionId}`)
                .then(response => response.json())
                .then(data => {
                    groupSelect.innerHTML = '<option value="">Все группы</option>';
                    data.forEach(group => {
                        groupSelect.innerHTML += `<option value="${group.id}">${group.name}</option>`;
                    });
                });
        } else {
            groupSelect.innerHTML = '<option value="">Все группы</option>';
            @foreach($groups as $group)
                groupSelect.innerHTML += `<option value="{{ $group->id }}">{{ $group->name }}</option>`;
            @endforeach
        }
    });
</script>
@endsection
@endsection