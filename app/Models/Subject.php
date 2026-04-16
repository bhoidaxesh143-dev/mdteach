<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
public function chapters()
{
    return $this->hasMany(
        \App\Models\Learning\Chapter::class
    )->orderBy('sort_order');
}

}
