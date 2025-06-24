<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Очистка таблиц с проверкой существования
        $tables = ['users', 'students', 'teachers', 'courses', 'schedules'];
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // Создаем администратора только если его нет
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@newtech.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );
        echo "Администратор: {$admin->email}\n";

        // Преподаватель
        $teacherUser = User::firstOrCreate(
            ['username' => 'teacher1'],
            [
                'email' => 'teacher1@newtech.com',
                'password' => Hash::make('password'),
                'role' => 'teacher'
            ]
        );

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $teacherUser->id],
            [
                'full_name' => 'Иванов Иван Иванович',
                'specialization' => 'Программирование',
                'bio' => 'Опытный преподаватель с 10-летним стажем'
            ]
        );

        // Студент
        $studentUser = User::firstOrCreate(
            ['username' => 'student1'],
            [
                'email' => 'student1@newtech.com',
                'password' => Hash::make('password'),
                'role' => 'student'
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'full_name' => 'Сидоров Алексей Петрович',
                'phone' => '+79161234567',
                'course_id' => 1 // Используем ID существующего курса
            ]
        );

        // Курс
        $course = Course::firstOrCreate(
            ['name' => 'Основы программирования'],
            [
                'description' => 'Базовый курс по программированию на Python',
                'duration_hours' => 40,
                'price' => 15000.00,
                'direction_id' => 1, // Добавьте ID существующего направления
                'subject_id' => 1,   // Добавьте ID существующего предмета
                'group_id' => 1,     // Добавьте ID существующей группы
                'course_level' => 1  // Укажите уровень курса
            ]
        );

        // Расписание
        Schedule::firstOrCreate(
            [
                'subject_id' => $course->subject_id, // Используем subject_id
                'teacher_id' => $teacher->id,
                'group_id' => $course->group_id,    // Добавляем group_id
                'start_time' => now()->addDays(1)
            ],
            [
                'end_time' => now()->addDays(1)->addHours(2),
                'classroom' => 'Аудитория 101'
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}