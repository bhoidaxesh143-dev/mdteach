<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'negative_marking',
        'total_marks',
        'is_active',
    'shuffle_questions',
    'max_attempts',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    'shuffle_questions' => 'boolean',
    'starts_at' => 'datetime',
    'ends_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
{
    return $this->hasMany(Question::class);
}
}
