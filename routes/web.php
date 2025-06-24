<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    StudentController,
    TeacherController,
    ScheduleController,
    FinanceController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты для студентов
Route::prefix('student')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::prefix('payments')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\PaymentController::class, 'index'])
            ->name('student.payments.index');
            
        Route::get('/create', [\App\Http\Controllers\Student\PaymentController::class, 'create'])
            ->name('student.payments.create');
            
        Route::post('/', [\App\Http\Controllers\Student\PaymentController::class, 'store'])
            ->name('student.payments.store');
    });
});
    
    // Платежи
    Route::prefix('payments')->group(function () {
        Route::get('/', [StudentPaymentController::class, 'index'])->name('student.payments');
        Route::get('/create', [StudentPaymentController::class, 'create'])->name('student.payments.create');
        Route::post('/', [StudentPaymentController::class, 'store'])->name('student.payments.store');
    });
    
    // Профиль
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::put('/profile/update', [StudentDashboardController::class, 'updateProfile'])->name('student.profile.update');

// Админ-панель
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Дашборд
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Специальные маршруты должны быть ДО ресурсов
    Route::get('/schedule/export', [ScheduleController::class, 'export'])->name('admin.schedule.export');
    Route::get('/finance/export', [FinanceController::class, 'export'])->name('admin.finance.export');
    Route::get('/admin/schedule/export', [ScheduleController::class, 'export'])
    ->name('admin.schedule.export')
    ->middleware(['auth', 'admin']);
    
    // Ресурсные маршруты
    Route::resource('students', StudentController::class)->names([
        'index' => 'admin.students.index',
        'create' => 'admin.students.create',
        'store' => 'admin.students.store',
        'show' => 'admin.students.show',
        'edit' => 'admin.students.edit',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy'
    ]);
    
    Route::resource('teachers', TeacherController::class)->names([
        'index' => 'admin.teachers.index',
        'create' => 'admin.teachers.create',
        'store' => 'admin.teachers.store',
        'edit' => 'admin.teachers.edit',
        'update' => 'admin.teachers.update',
        'destroy' => 'admin.teachers.destroy'
    ])->except(['show']);
    
    Route::resource('schedule', ScheduleController::class)->names([
        'index' => 'admin.schedule.index',
        'create' => 'admin.schedule.create',
        'store' => 'admin.schedule.store',
        'edit' => 'admin.schedule.edit',
        'update' => 'admin.schedule.update',
        'destroy' => 'admin.schedule.destroy'
    ]);
    
    // Финансы (не ресурсный контроллер)
    Route::get('/finance', [FinanceController::class, 'index'])->name('admin.finance.index');
    Route::post('/finance/update-status/{payment}', [FinanceController::class, 'updatePaymentStatus'])
        ->name('admin.finance.update-status');
});

// Временный диагностический маршрут
Route::get('/debug', function() {
    return response()->json([
        'routes' => Route::getRoutes()->getRoutesByName(),
        'current' => request()->fullUrl()
    ]);
});