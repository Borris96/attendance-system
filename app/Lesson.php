<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function substitutes() // 这个课程可能会有很多代课记录
    {
        return $this->hasMany(Substitute::class);
    }

    public function alters() // 这个课程可能会有很多代课记录
    {
        return $this->hasMany(Alter::class);
    }

    public function lessonUpdates()
    {
        return $this->hasMany(LessonUpdate::class);
    }
    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $ew_start_time
     * @param timestamp $ew_end_time
     * @param timestamp $old_ew_start_time
     * @param timestamp $old_ew_end_time
     * @return boolean
     */

    public static function isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time)
    {
        if ($ew_end_time<=$old_ew_start_time || $old_ew_end_time<=$ew_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }

    // 删除课程
    public static function delLesson($id)
    {
        $lesson = Lesson::find($id);
        $lesson_updates = $lesson->lessonUpdates;
        // 不仅要删除课程本身，还要将其关联的所有老师的实际排课时长删除
        foreach ($lesson_updates as $lu) {
            $start_date = $lu->start_date;
            $end_date = $lu->end_date;
            if ($lu->teacher_id != null)
            {
                Teacher::calTermDuration($start_date, $end_date, $lu, $option = 'substract');
            }
            $lu->delete();
        }
        $lesson->delete();
    }

    public static function changeTeacher($id,$current_teacher_id,$effective_date)
    {

    }
}
