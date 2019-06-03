<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkHistory extends Model
{
    protected $table = 'work_historys';

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
