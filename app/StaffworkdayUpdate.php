<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffworkdayUpdate extends Model
{
    protected $table = 'staffworkday_updates';

    public function staffworkday()
    {
        return $this->belongsTo(Staffworkday::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function staffworkdayUpdates()
    {
        return $this->hasMany(StaffworkdayUpdate::class);
    }
}
