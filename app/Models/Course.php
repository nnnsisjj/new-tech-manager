<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Добавлен импорт
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Добавлен импорт

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_hours',
        'price',
        'direction_id'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }
}