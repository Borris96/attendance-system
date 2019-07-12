<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
