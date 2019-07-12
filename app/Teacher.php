<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
