<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
{
    $student = Auth::user()->student;
    $payments = $student->payments()
        ->with('direction')
        ->latest()
        ->get();
    
    // Группируем платежи по направлениям и рассчитываем остатки
    $directionsWithPayments = $payments->groupBy('direction_id')->map(function ($payments) {
        $direction = $payments->first()->direction;
        $paid = $payments->where('status', 'completed')->sum('amount');
        $remaining = max(0, $direction->price - $paid);
        
        return [
            'direction' => $direction,
            'payments' => $payments,
            'paid' => $paid,
            'remaining' => $remaining
        ];
    });

    return view('student.payments.index', [
        'directionsWithPayments' => $directionsWithPayments
    ]);
}

public function create()
{
    // Получаем ВСЕ направления из базы данных
    $directions = Direction::with('courses')
        ->orderBy('name')
        ->get();

    return view('student.payments.create', [
        'directions' => $directions,
        'student' => Auth::user()->student // Передаем студента для предварительного выбора
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:1',
        'payment_method' => 'required|string|in:card,transfer,cash',
        'direction_id' => 'required|exists:directions,id'
    ]);

    $student = Auth::user()->student;
    $direction = Direction::findOrFail($validated['direction_id']);

    // Получаем первый активный курс по направлению
    $course = $direction->courses()->where('is_active', true)->first();

    if (!$course) {
        return back()
            ->withErrors(['direction_id' => 'Для выбранного направления нет активных курсов'])
            ->withInput();
    }

    // Проверка суммы платежа
    $paidAmount = $direction->getPaidAmount($student->id);
    $remaining = $direction->price - $paidAmount;

    if ($validated['amount'] > $remaining) {
        return back()
            ->withErrors(['amount' => 'Сумма платежа превышает остаток к оплате'])
            ->withInput();
    }

    // Обновление курса студента при необходимости
    if (!$student->course_id) {
        $student->update(['course_id' => $course->id]);
    }

    // Создание платежа
    $payment = $student->payments()->create([
        'amount' => $validated['amount'],
        'payment_method' => $validated['payment_method'],
        'payment_date' => now(),
        'course_id' => $course->id,
        'direction_id' => $direction->id,
        'status' => 'pending'
    ]);

    return redirect()
        ->route('student.payments.index')
        ->with('success', 'Платеж успешно создан. Ожидайте подтверждения администратора.');
}
}