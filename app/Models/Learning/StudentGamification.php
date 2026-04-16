<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class StudentGamification extends Model
{
    protected $fillable = [
        'student_id',
        'xp',
        'level',
        'streak_days',
        'last_activity_date',
    ];

    protected $casts = [
        'last_activity_date' => 'date'
    ];
}
