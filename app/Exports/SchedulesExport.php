<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SchedulesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return $this->schedules;
    }

    public function headings(): array
    {
        return [
            'Группа',
            'Курс',
            'Направление',
            'Предмет',
            'Преподаватель',
            'Дата начала',
            'Дата окончания',
            'Аудитория'
        ];
    }

    public function map($schedule): array
    {
        return [
            $schedule->group->name ?? '—',
            $schedule->group->course_level.' курс' ?? '—',
            $schedule->group->direction->name ?? '—',
            $schedule->subject->name ?? '—',
            $schedule->teacher->full_name ?? '—',
            $schedule->start_time->format('d.m.Y H:i'),
            $schedule->end_time->format('d.m.Y H:i'),
            $schedule->classroom
        ];
    }
}