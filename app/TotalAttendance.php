<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalAttendance extends Model
{
    protected $table = 'total_attendances';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
}
