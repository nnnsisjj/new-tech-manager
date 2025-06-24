<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:100',
            'bio' => 'nullable|string'
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher'
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'specialization' => $validated['specialization'],
            'bio' => $validated['bio']
        ]);

        return redirect()->route('admin.teachers.index')
               ->with('success', 'Преподаватель успешно добавлен');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:100',
            'bio' => 'nullable|string'
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')
               ->with('success', 'Данные преподавателя обновлены');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user()->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
               ->with('success', 'Преподаватель удален');
    }
}