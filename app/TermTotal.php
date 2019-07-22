<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermTotal extends Model
{
    protected $table = 'term_totals';

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
