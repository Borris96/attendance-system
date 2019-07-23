<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonAttendance extends Model
{

    /**
     * 计算学期中一个员工一个月的实际上课时长
     * @param date $month_first_day Y-m-d
     * @param date $month_last_day Y-m-d
     * @param int $term_id
     * @param decimal $actual_duration 实际排课
     * @param int $teacher_id
     * @return decimal $actual_goes
     *
     */
    public static function monthActualGo($month_first_day, $month_last_day, $term_id, $actual_duration, $teacher_id)
    {
            $month_total_missing = 0;
            $month_total_substitute = 0;
            $actual_goes = 0;
            // 这个老师当月的代课缺课记录
            $this_teacher_missings = Substitute::where('term_id',$term_id)->where('lesson_date','>=',$month_first_day)->where('lesson_date','<=',$month_last_day)->where('teacher_id',$teacher_id)->get();
            $this_teacher_substitutes = Substitute::where('term_id',$term_id)->where('lesson_date','>=',$month_first_day)->where('lesson_date','<=',$month_last_day)->where('substitute_teacher_id',$teacher_id)->get();


            if (count($this_teacher_substitutes)!=0)
            {
                foreach ($this_teacher_substitutes as $tts) {
                    // 此处需要注意，这里的duration是最新的时长，如果查询的是3月的，而课程5月份时长变了，这个duration数值获取是不准的，缺课记录同理
                    $month_total_substitute += $tts->duration;
                }
            }

            if (count($this_teacher_missings)!=0)
            {
                foreach ($this_teacher_missings as $ttm) {
                    $month_total_missing += $ttm->duration;
                }
            }

            $actual_goes = $actual_duration + $month_total_substitute - $month_total_missing; // 实际上课时长
            return $actual_goes;
    }

    /**
     * 确定学期中这个月的起止时间
     *
     * @param date $term_start_date Y-m-d
     * @param date $term_end_date Y-m-d
     * @param date $s_m_y Y-m 搜索的 年-月
     * @param array $month_f_l[]
     * @return array $month_f_l[]; include: $month_first_day Y-m-d, $month_last_day Y-m-d
     *
     */
    public static function decideMonthFirstLast($term_start_date, $term_end_date, $s_m_y, $month_f_l=[])
    {
        $start_year = date('Y',strtotime($term_start_date));
        $end_year = date('Y',strtotime($term_end_date));

        $term_first_month = $start_year.'-'.date('m',strtotime($term_start_date));
        $term_last_month = $end_year.'-'.date('m',strtotime($term_end_date));

        if (strtotime($s_m_y) == strtotime($term_first_month)) // 是学期第一个月
        {
            $month_first_day = date('Y-m-d',strtotime($term_start_date)); // 学期开始日
            $month_fake_first_day = date('Y-m-01',strtotime($s_m_y));
            $month_last_day = date('Y-m-d', strtotime("$month_fake_first_day +1 month -1 day"));// 第一个月月底
        }
        elseif (strtotime($s_m_y) == strtotime($term_last_month)) // 是学期最后一个月
        {
                $month_first_day = date('Y-m-01',strtotime($s_m_y));
                // $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                $month_last_day = date('Y-m-d',strtotime($term_end_date)); // 学期结束日
        }
        else // 是学期中或者学期外
        {
            $month_first_day = date('Y-m-01',strtotime($s_m_y));
            $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
        }
        $month_f_l[] = $month_first_day;
        $month_f_l[] = $month_last_day;

        return $month_f_l;
    }
}
