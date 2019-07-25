<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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

    public static function exportTotalOne($spreadsheet, $start_month, $term)
    {
        // dump($start_month);
        // dump($term);
        // exit();
        $worksheet = $spreadsheet->getSheet(0);
        $title = $term->term_name.'_'.$start_month.' 考勤汇总';
        $worksheet->setTitle('考勤汇总');
        $worksheet->setCellValueByColumnAndRow(1, 1, '考勤汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计时间:');
        $worksheet->setCellValueByColumnAndRow(2, 2, $term->term_name.'-'.$start_month.'月');
        $worksheet->setCellValueByColumnAndRow(1, 3, '老师编号');
        $worksheet->setCellValueByColumnAndRow(2, 3, '英文名');
        $worksheet->setCellValueByColumnAndRow(3, 3, '老师类型');
        $worksheet->setCellValueByColumnAndRow(4, 3, '应排课');
        $worksheet->setCellValueByColumnAndRow(5, 3, '实际排课');
        $worksheet->setCellValueByColumnAndRow(6, 3, '实际上课');
        $worksheet->setCellValueByColumnAndRow(7, 3, '缺少课时');

        $worksheet->mergeCells('A1:G1'); // 合并第一行单元格

        $title_array = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $content_array = [
            'font' => [
                'bold' => false
            ],
            // 'alignment' => [
            //     'horizontal' => Alignment::HORIZONTAL_CENTER,
            // ],
        ];

        $worksheet->getStyle('A1')->applyFromArray($title_array)->getFont()->setSize(24);
        $worksheet->getStyle('A2:B2')->applyFromArray($content_array)->getFont()->setSize(10);
        $worksheet->getStyle('A3:G3')->applyFromArray($title_array)->getFont()->setSize(9);

        $term_id = $term->id;
        $start_year = date('Y',strtotime($term->start_date));
        $end_year = date('Y',strtotime($term->end_date));
        $term_first_month = $start_year.'-'.date('m',strtotime($term->start_date));
        $term_last_month = $end_year.'-'.date('m',strtotime($term->end_date));
        // 学期的开始结束年份
        // 查询的开始年月
        $search_start_year = date('Y',strtotime($term->start_date));
        $search_start_month = $start_month;
        // 查询的 年-月
        $s_m_y = $search_start_year.'-'.$search_start_month;
        if (strtotime($s_m_y)<strtotime($term_first_month)) // 如果不在范围内，说明年数少一年
        {
            $search_start_year += 1;
            // 新的查询 年-月
            $s_m_y = $search_start_year.'-'.$search_start_month;
        }

        $month_f_l = LessonAttendance::decideMonthFirstLast($term->start_date, $term->end_date, $s_m_y);
        $month_first_day = $month_f_l[0];
        $month_last_day = $month_f_l[1];

        // 查询学期中这个月所有老师实际排课
        $this_month_durations = MonthDuration::where('term_id',$term_id)->where('month',$search_start_month)->get();

        $teacher_ids = [];
        $actual_durations = []; // 实际排课时长
        $teachers = []; // 老师
        $actual_goes = []; // 实际上课时长
        $should_durations = []; // 应该上课时长
        $teachers = [];

        foreach ($this_month_durations as $d) {
            $teacher_ids[] = $d->teacher_id;
            $actual_durations[] = $d->actual_duration;
            $actual_goes[] = LessonAttendance::monthActualGo($month_first_day, $month_last_day, $term_id, $d->actual_duration, $d->teacher_id); // 实际上课时长

            // 针对开学首周和期末最后一周的应该时常计算
            if ($month_first_day == $term->start_date) // 首月补满首周
            {
                while (date('w',strtotime($month_first_day)) != 1)
                {
                    $month_first_day = date('Y-m-d', strtotime("$month_first_day -1 day"));
                }
            }
            elseif ($month_last_day == $term->end_date) // 末月补满末周
            {
                while (date('w',strtotime($month_last_day)) != 0)
                {
                    $month_last_day = date('Y-m-d', strtotime("$month_last_day +1 day"));
                }
                // dump($month_last_day);
                // exit();
            }
            $teachers[] = Teacher::find($d->teacher_id);
            $should_durations[] = Teacher::calShouldMonthDuration(Teacher::find($d->teacher_id), $month_first_day,$month_last_day); // 应该排课
        }

        $count = count($teachers);
        foreach ($teachers as $key=>$t)
        {
            $worksheet->setCellValueByColumnAndRow(1, 4+$key, $t->staff->id);
            $worksheet->setCellValueByColumnAndRow(2, 4+$key, $t->staff->englishname);
            $worksheet->setCellValueByColumnAndRow(3, 4+$key, $t->staff->position_name);
            $worksheet->setCellValueByColumnAndRow(4, 4+$key, $should_durations[$key]);
            $worksheet->setCellValueByColumnAndRow(5, 4+$key, $actual_durations[$key]);
            $worksheet->setCellValueByColumnAndRow(6, 4+$key, $actual_goes[$key]);
            if (($should_durations[$key] - $actual_goes[$key])>0)
            {
                $worksheet->setCellValueByColumnAndRow(7, 4+$key, $should_durations[$key] - $actual_goes[$key]);
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(7, 4+$key, 0);
            }
        }


        $worksheet->getStyle('A4:G'.($count+4))->applyFromArray($content_array)->getFont()->setSize(9);
        $worksheet->getStyle('A3:G'.($count+4))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(10);
        $worksheet->getColumnDimension('B')->setWidth(10);
        $worksheet->getColumnDimension('C')->setWidth(10);



        // 下载
        $filename = $title.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
