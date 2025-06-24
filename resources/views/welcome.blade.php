<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Главная</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2575fc;
        }
        .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
        }
    </style>
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
            <div class="navbar-nav">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Панель управления</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Вход</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">New Tech Manager</h1>
            <p class="lead">Профессиональное решение для администраторов учебных заведений</p>
            @guest
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Начать работу</a>
            @endguest
        </div>
    </div>

    <div class="container">
        <div class="row text-center g-4 py-5">
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>Студенты</h3>
                <p>Полный контроль над студенческими данными и успеваемостью</p>
                @auth
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary">Управление</a>
                @endauth
            </div>

            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3>Преподаватели</h3>
                <p>Управление преподавательским составом и нагрузкой</p>
                @auth
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-primary">Управление</a>
                @endauth
            </div>

            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3>Расписание</h3>
                <p>Гибкое составление и редактирование расписания</p>
                @auth
                <a href="{{ route('admin.schedule.index') }}" class="btn btn-outline-primary">Управление</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Подвал -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </footer>

    <!-- Скрипты -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>