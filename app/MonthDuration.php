<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthDuration extends Model
{
    protected $table = 'month_durations';

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
