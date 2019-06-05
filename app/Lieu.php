<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lieu extends Model
{
    protected $table = 'lieus';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }
}
