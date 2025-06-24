<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Direction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SchedulesExport;
use App\Models\Course;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['group.direction', 'subject', 'teacher'])
            ->orderBy('start_time', 'asc');

        if ($request->filled('direction_id')) {
            $query->whereHas('group', fn($q) => $q->where('direction_id', $request->direction_id));
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $schedules = $query->paginate(10);

        return view('admin.schedule.index', [
            'schedules' => $schedules,
            'groups' => Group::with('direction')->get(),
            'subjects' => Subject::all(),
            'teachers' => Teacher::all(),
            'directions' => Direction::all(),
            'currentFilters' => $request->only(['direction_id', 'group_id', 'teacher_id'])
        ]);
    }

    public function create()
{
    return view('admin.schedule.create', [
        'groups' => Group::with('direction')->get(),
        'teachers' => Teacher::all(),
        'subjects' => Subject::all(), // Добавлено
        'directions' => Direction::all(),
        'classrooms' => [
            'Ауд. 101',
            'Ауд. 102',
            'Ауд. 201',
            'Ауд. 202',
            'Ауд. 301', 
            'Ауд. 302',
            'Лаб. 1',
            'Лаб. 2'
        ]
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'group_id' => 'required|exists:groups,id',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'required|exists:teachers,id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'classroom' => 'required|string|max:50'
    ]);

    // Проверка занятости аудитории
    $isOccupied = Schedule::where('classroom', $validated['classroom'])
        ->where(function($query) use ($validated) {
            $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhere(function($q) use ($validated) {
                      $q->where('start_time', '<', $validated['start_time'])
                        ->where('end_time', '>', $validated['end_time']);
                  });
        })
        ->exists();

    if ($isOccupied) {
        return back()->withErrors(['classroom' => 'Аудитория уже занята в это время'])->withInput();
    }

    Schedule::create($validated);

    return redirect()->route('admin.schedule.index')
        ->with('success', 'Занятие успешно добавлено');
}

    public function edit(Schedule $schedule)
    {
        return view('admin.schedule.edit', [
            'schedule' => $schedule,
            'groups' => Group::with('direction')->get(),
            'subjects' => Subject::all(),
            'teachers' => Teacher::all(),
            'directions' => Direction::all()
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'classroom' => 'required|string|max:50'
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Изменения сохранены');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedule.index')
            ->with('success', 'Занятие удалено');
    }

    public function export(Request $request)
    {
        $query = Schedule::with(['group.direction', 'subject', 'teacher'])
            ->orderBy('start_time', 'asc');

        if ($request->filled('direction_id')) {
            $query->whereHas('group', fn($q) => $q->where('direction_id', $request->direction_id));
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $schedules = $query->get();

        return Excel::download(
            new SchedulesExport($schedules),
            'schedule_'.now()->format('Y-m-d').'.xlsx'
        );
    }
}