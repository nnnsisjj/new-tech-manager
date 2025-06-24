<div class="sidebar bg-dark text-white collapse d-md-block" id="sidebar">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Дашборд
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.students.index') }}">
                    <i class="fas fa-users me-2"></i> Студенты
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.teachers.index') }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Преподаватели
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.schedule.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i> Расписание
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.finance.index') }}">
                    <i class="fas fa-money-bill-wave me-2"></i> Финансы
                </a>
            </li>
            @if(auth()->user()->isStudent())
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('student.payments') }}">
            <i class="fas fa-money-bill-wave me-2"></i> Мои платежи
        </a>
    </li>
@endif
        </ul>
    </div>
</div>

<style>
    .sidebar {
        width: 280px;
        min-height: 100vh;
    }
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.75);
        padding: 0.75rem 1.5rem;
    }
    .sidebar .nav-link:hover, 
    .sidebar .nav-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }
</style>