@extends('admin.layouts.app')

@section('title', 'Добавить занятие в расписание')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Добавить занятие</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedule.store') }}">
                @csrf

                <div class="row mb-3">
                    <!-- Выбор направления -->
                    <div class="col-md-4">
                        <label class="form-label">Направление *</label>
                        <select class="form-select" id="direction-selector" required>
                            <option value="">-- Выберите направление --</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}">{{ $direction->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Выбор группы -->
                    <div class="col-md-4">
                        <label class="form-label">Группа *</label>
                        <select name="group_id" id="group-selector" class="form-select" required>
                            <option value="">-- Сначала выберите направление --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        </select>
                    </div>

                   
                </div>

                <div class="row mb-3">
    <!-- Выбор предмета -->
    <div class="col-md-4">
        <label class="form-label">Предмет *</label>
        <select name="subject_id" class="form-select" required>
            <option value="">-- Выберите предмет --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" 
                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Выбор преподавателя -->
    <div class="col-md-4">
        <label class="form-label">Преподаватель *</label>
        <select name="teacher_id" class="form-select" required>
            <option value="">-- Выберите преподавателя --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" 
                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->full_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

                    <!-- Дата и время начала -->
                    <div class="row mb-3">
    <!-- Дата и время начала -->
    <div class="col-md-4">
        <label class="form-label">Дата и время начала *</label>
        <input type="datetime-local" name="start_time" id="start-time" 
               class="form-control" value="{{ old('start_time') }}" required>
    </div>

    <!-- Дата и время окончания -->
    <div class="col-md-4">
        <label class="form-label">Дата и время окончания *</label>
        <input type="datetime-local" name="end_time" id="end-time" 
               class="form-control" value="{{ old('end_time') }}" required>
    </div>
</div>
                <div class="row mb-3">
                    <!-- Выбор аудитории -->
                    <div class="col-md-4">
                        <label class="form-label">Аудитория *</label>
                        <select name="classroom" id="classroom-selector" class="form-select" required>
                            <option value="">-- Выберите аудиторию --</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom }}" 
                                    {{ old('classroom') == $classroom ? 'selected' : '' }}>
                                    {{ $classroom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                        <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Динамическая загрузка групп и курсов при выборе направления
        $('#direction-selector').change(function() {
            const directionId = $(this).val();
            
            if (directionId) {
                // Загрузка групп
                $.get(`/api/groups?direction_id=${directionId}`, function(groups) {
                    $('#group-selector').html('<option value="">-- Выберите группу --</option>');
                    groups.forEach(group => {
                        $('#group-selector').append(
                            `<option value="${group.id}">${group.name}</option>`
                        );
                    });
                });
                
                // Загрузка курсов
                $.get(`/api/courses?direction_id=${directionId}`, function(courses) {
                    $('#course-selector').html('<option value="">-- Выберите курс --</option>');
                    courses.forEach(course => {
                        $('#course-selector').append(
                            `<option value="${course.id}">${course.name}</option>`
                        );
                    });
                });
            } else {
                $('#group-selector').html('<option value="">-- Сначала выберите направление --</option>');
                $('#course-selector').html('<option value="">-- Сначала выберите направление --</option>');
            }
        });

        // Проверка занятости аудиторий при изменении времени
        $('#start-time, #end-time').change(function() {
            const startTime = $('#start-time').val();
            const endTime = $('#end-time').val();
            
            if (startTime && endTime) {
                $.get(`/api/occupied-classrooms?start=${startTime}&end=${endTime}`, function(occupied) {
                    $('#classroom-selector option').each(function() {
                        const classroom = $(this).val();
                        if (classroom && classroom !== '') {
                            $(this).prop('disabled', occupied.includes(classroom));
                            if (occupied.includes(classroom)) {
                                $(this).attr('title', 'Аудитория занята в это время');
                            } else {
                                $(this).removeAttr('title');
                            }
                        }
                    });
                });
            }
        });
    });
</script>
@endsection
@endsection