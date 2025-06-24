<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Получаем аутентифицированного пользователя
        $user = Auth::user();
        
        // Проверяем наличие связанной записи студента
        if (!$user->student) {
            abort(403, 'Профиль студента не найден');
        }

        return view('student.dashboard', [
            'user' => $user,
            'student' => $user->student
        ]);
    }
}

    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'course' => 'required|string|max:100'
        ]);

        $student = Auth::user()->student;
        $student->update($request->only(['phone', 'course']));

        return redirect()->route('student.profile')->with('success', 'Профиль обновлен');
    }
}