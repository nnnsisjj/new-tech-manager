<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'course'
    ];

protected $casts = [
    'course' => 'array' // Добавляем автоматическое преобразование JSON в массив
];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
{
    return $this->hasMany(Payment::class);
}
public function courses()
{
    return $this->belongsToMany(Course::class, 'course_student');
}
public function course()
{
    return $this->belongsTo(Course::class);
}

public function getCourseNameAttribute()
{
    if (is_array($this->course)) {
        return $this->course['name'] ?? 'Курс не указан';
    }
    
    // Если курс хранится как JSON-строка
    $courseData = json_decode($this->course, true);
    return $courseData['name'] ?? 'Курс не указан';
}

public function direction()
{
    return $this->hasOneThrough(
        Direction::class,
        Course::class,
        'id', // Ключ в таблице courses
        'id', // Ключ в таблице directions
        'course_id', // Внешний ключ в students
        'direction_id' // Внешний ключ в courses
    );
}
}