<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }
}
