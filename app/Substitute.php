<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Substitute extends Model
{
    protected $table = 'substitutes';

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // 缺课老师
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // 如何与代课老师建立关联？
    public function subTeacher()
    {
        return $this->belongsTo(Teacher::class,'substitute_teacher_id'); // 获取代课老师的信息
    }

}
