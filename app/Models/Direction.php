<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'price',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmount($studentId)
    {
        return $this->payments()
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->sum('amount');
    }
}