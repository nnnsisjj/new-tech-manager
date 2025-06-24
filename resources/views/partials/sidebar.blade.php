<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Панель управления
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.students.index') }}">
                    <i class="fas fa-users me-2"></i>Студенты
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.schedule.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>Расписание
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.finance.index') }}">
                    <i class="fas fa-money-bill-wave me-2"></i>Финансы
                </a>
            </li>
        </ul>
    </div>
</div>