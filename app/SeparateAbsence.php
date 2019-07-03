<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparateAbsence extends Model
{
    protected $table = 'separate_absences';
    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

}
