<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{

public function index()
{
    $students = Student::with('user')->paginate(10);
    return view('admin.students.index', compact('students'));
}
public function create()
{
    return view('admin.students.create', [
        'courses' => \App\Models\Course::all() // Предполагая, что у вас есть модель Course
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'course' => 'required|integer|exists:courses,id'
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student'
        ]);

        Student::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'course+id' => $validated['course']
        ]);

        return redirect()->route('admin.students.index')
               ->with('success', 'Студент успешно добавлен');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'course' => 'required|string|max:100'
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')
               ->with('success', 'Данные студента обновлены');
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        $student->delete();

        return redirect()->route('admin.students.index')
               ->with('success', 'Студент удален');
    }
}