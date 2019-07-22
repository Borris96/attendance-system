<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonUpdate extends Model
{
    protected $table = 'lesson_updates';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
