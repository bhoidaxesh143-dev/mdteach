<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'subject_id',
        'title',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }


public function topics()
{
    return $this->hasMany(
        \App\Models\Learning\Topic::class
    )->whereNull('parent_id')
     ->orderBy('sort_order');
}
}
