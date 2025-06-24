<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'direction_id',
        'amount',
        'payment_method',
        'payment_date',
        'status'
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];
}