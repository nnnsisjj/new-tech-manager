<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;

class FinanceController extends Controller
{
    public function index()
{
    $payments = Payment::with(['student', 'direction'])
        ->orderBy('payment_date', 'desc')
        ->paginate(15);
    
    $totalIncome = Payment::where('status', 'completed')->sum('amount');
    
    // Получаем студентов с их направлениями и платежами
    $studentsWithDebt = Student::whereHas('payments') // Только студенты с платежами
        ->with(['direction', 'payments'])
        ->whereHas('direction')
        ->get()
        ->map(function($student) {
            $paid = $student->payments
                ->where('status', 'completed')
                ->sum('amount');
            
            $directionPrice = $student->direction->price;
            $debt = max(0, $directionPrice - $paid);
            
            return [
                'student' => $student,
                'direction' => $student->direction->name,
                'direction_price' => $directionPrice,
                'paid' => $paid,
                'debt' => $debt
            ];
        })
        ->filter(function($item) {
            // Оставляем только тех, у кого есть платежи (даже если долг = 0)
            return $item['paid'] > 0 || $item['debt'] > 0;
        })
        ->sortByDesc('debt');
    
    return view('admin.finance.index', [
        'payments' => $payments,
        'totalIncome' => $totalIncome,
        'studentsWithDebt' => $studentsWithDebt
    ]);
}
    public function updatePaymentStatus(Request $request, Payment $payment)
{
    $request->validate([
        'status' => 'required|in:pending,completed,rejected'
    ]);

    $payment->update(['status' => $request->status]);

    return back()->with('success', 'Статус платежа обновлен');
}

public function export()
{
    $payments = Payment::with(['student'])
        ->orderBy('payment_date', 'desc')
        ->get();

    return Excel::download(
        new PaymentsExport($payments),
        'payments_'.now()->format('Y-m-d').'.xlsx'
    );
}
}