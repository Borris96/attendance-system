<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alter extends Model
{
    protected $table = 'alters';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
