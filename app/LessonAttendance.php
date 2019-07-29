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


    /**
     * 导出单月老师上课考勤
     * @param object $spreadsheet
     * @param int $start_month
     * @param object $term
     * @param string $option 兼职或全职
     *
     */
    public static function exportTotalOne($spreadsheet, $start_month, $term, $option)
    {
        $worksheet = $spreadsheet->getSheet(0);
        $title = $term->term_name.'_'.$start_month.'月 '.$option.'考勤汇总';
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
        $worksheet->getStyle('A3:G3')->applyFromArray($title_array)->getFont()->setSize(12);

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
            $teacher = Teacher::find($d->teacher_id);
            if ($teacher->staff->position_name == $option)
            {
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


        $worksheet->getStyle('A4:G'.($count+4))->applyFromArray($content_array)->getFont()->setSize(12);
        $worksheet->getStyle('A3:G'.($count+4))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(15);
        $worksheet->getColumnDimension('B')->setWidth(15);
        $worksheet->getColumnDimension('C')->setWidth(15);
        $worksheet->getColumnDimension('D')->setWidth(15);
        $worksheet->getColumnDimension('E')->setWidth(15);
        $worksheet->getColumnDimension('F')->setWidth(15);
        $worksheet->getColumnDimension('G')->setWidth(15);


        // 下载
        $filename = $title.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * 导出多月老师上课考勤，每一张工作表包含一个老师的多月上课数据和请假数据
     * @param object $spreadsheet
     * @param int $start_month
     * @param object $term
     *
     */
    public static function exportTotalMultiple($spreadsheet, $start_month, $end_month, $term, $option)
    {
        $title = $term->term_name.'_'.$start_month.'月'.'~'.$end_month.'月 '.$option.'考勤汇总';
        $term_id = $term->id;
        $start_year = date('Y',strtotime($term->start_date));
        $end_year = date('Y',strtotime($term->end_date));
        $term_first_month = $start_year.'-'.date('m',strtotime($term->start_date));
        $term_last_month = $end_year.'-'.date('m',strtotime($term->end_date));

        // 判断这两个月是否都包含在学期中
        // 查询的开始年月
        $search_start_year = $start_year;
        $search_start_month = $start_month;
        // 查询的 年-月
        $s_m_y = $search_start_year.'-'.$search_start_month;
        if (strtotime($s_m_y)<strtotime($term_first_month)) // 如果不在范围内，说明年数少一年
        {
            $search_start_year += 1;
            // 新的查询 年-月
            $s_m_y = $search_start_year.'-'.$search_start_month;
        }

        // 查询的结束年月
        $search_end_year = $end_year;
        $search_end_month = $end_month;
        // 查询的 年-月
        $e_m_y = $search_end_year.'-'.$search_end_month;

        if (strtotime($e_m_y)>strtotime($term_last_month)) // 如果不在范围内，说明年数多一年
        {
            $search_end_year -= 1;
            // 新的查询 年-月
            $e_m_y = $search_end_year.'-'.$search_end_month;
        }

        // 按每个老师查询
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->start_date)->get();

        $all_teacher_durations = []; // 储存所有老师每个月的课时
        $all_teacher_total_durations = []; // 储存所有老师这个学期合计的课时
        foreach ($teachers as $index=>$t)
        {
                // 准备数据
                // 从查询开始月遍历到结束月,如果到12月了年份+1
                $search_year = $search_start_year;
                $search_month = $search_start_month;
                $search_m_y = $search_year.'-'.$search_month;
                $teacher_durations[] = array();
                // $teacher_durations[][0]: 应排课, $teacher_durations[][1]: 实际排课, $teacher_durations[][2]: 实际上课, $teacher_durations[][3]: 缺少课时

                // 只要遍历的 年-月 还没有到查询终止月，就一直录数据
                while (strtotime($search_m_y)<=strtotime($e_m_y))
                {
                    $month_f_l = LessonAttendance::decideMonthFirstLast($term->start_date, $term->end_date, $search_m_y);
                    $month_first_day = $month_f_l[0];
                    $month_last_day = $month_f_l[1];

                    // 查询学期中这个月这个老师的实际排课
                    $this_month_duration = MonthDuration::where('term_id',$term->id)->where('teacher_id',$t->id)->where('month',$search_month);
                    if (count($this_month_duration->get()) == 0) // 这个月没有实际排课
                    {
                        $teacher_durations[$search_month][1] = 0;
                    }
                    else // 查到排课的情况
                    {
                        // 实际排课
                        $teacher_durations[$search_month][1] = $this_month_duration->value('actual_duration');
                    }
                    // 应排课
                    $teacher_durations[$search_month][0] = Teacher::calShouldMonthDuration($t, $month_first_day,$month_last_day);
                    // 实际上课
                    $teacher_durations[$search_month][2] = LessonAttendance::monthActualGo($month_first_day, $month_last_day, $term_id, $teacher_durations[$search_month][1], $t->id);
                    // 缺少课时
                    $teacher_durations[$search_month][3] = $teacher_durations[$search_month][0]-$teacher_durations[$search_month][2];
                    if ($teacher_durations[$search_month][3]<0) // 如果实际上课比应上课多，缺少课时归零
                    {
                        $teacher_durations[$search_month][3] = 0;
                    }

                    // 进入下一个月
                    if ($search_month == 12)
                    {
                        $search_year +=1;
                        $search_month = 1;
                    }
                    else
                    {
                        $search_month += 1;
                    }
                    $search_m_y = $search_year.'-'.$search_month;

                }
                $actual_duration = 0; // 实际排课合计
                $actual_goes = 0; // 实际上课合计
                $should_duration = 0; // 应排课合计
                $lack_duration = 0; // 缺课时合计
                foreach ($teacher_durations as $d) {
                    if ($d != null)
                    {
                        $should_duration += $d[0];
                        $actual_duration += $d[1];
                        $actual_goes += $d[2];
                        $lack_duration += $d[3];
                    }
                }
                $all_teacher_durations[] = $teacher_durations; // 所有老师所有查询月的数据合集
                $all_teacher_total_durations[$index][0] = $should_duration; // 所有老师应排课时长合计
                $all_teacher_total_durations[$index][1] = $actual_duration; // 所有老师实际排课时长合计
                $all_teacher_total_durations[$index][2] = $actual_goes; // 老师的实际上课时长合计
                $all_teacher_total_durations[$index][3] = $lack_duration; // 老师的缺少课时合计

                $term_start_date = date('Y-m-d H:i:s',strtotime($term->start_date));
                $term_end_date = date('Y-m-d H:i:s',strtotime($term->end_date));

                // 加班目前取学期中的所有加班记录(除了调休加班外)
                // $extra_works = ExtraWork::where('staff_id',$t->staff->id)->where('extra_work_type','<>','调休')->where('extra_work_end_time','<=',$term_end_date)->where('extra_work_end_time','>=',$term_start_date)->get();
                $extra_works = ExtraWork::where('staff_id',$t->staff->id)->where('extra_work_end_time','<=',$term_end_date)->where('extra_work_end_time','>=',$term_start_date)->get();
                // 计算总计加班工时（转换后）
                $total = 0;
                foreach ($extra_works as $ew) {
                    if ($ew->extra_work_type == '带薪 1:1.2')
                    {
                        $total += ($ew->duration)*1.2;
                    }
                    else
                    {
                        $total += ($ew->duration)*1.0;
                    }

                }
                $all_teacher_extra_works[$index] = $extra_works;// 所有老师的加班记录
                $all_teacher_extra_work_durations[$index] = $total; //所有老师加班记录总时长
        }

        foreach ($teachers as $key=>$t)
        {
            $j = 0; // 新建表单用
            if ($t->staff->position_name == $option)
            {
                // dump($key);
                $worksheet = $spreadsheet->createSheet($j);

                $worksheet->setTitle($t->staff->englishname);
                $worksheet->setCellValueByColumnAndRow(1, 1, '考勤汇总表'); // (列，行)
                $worksheet->setCellValueByColumnAndRow(1, 2, '统计时间:');
                $worksheet->setCellValueByColumnAndRow(2, 2, $term->term_name.'-'.$start_month.'月'.'~'.$end_month.'月');
                $worksheet->setCellValueByColumnAndRow(1, 3, $t->staff->englishname);
                $worksheet->setCellValueByColumnAndRow(1, 4, '月份');
                $worksheet->setCellValueByColumnAndRow(2, 4, '应排课');
                $worksheet->setCellValueByColumnAndRow(3, 4, '实际排课');
                $worksheet->setCellValueByColumnAndRow(4, 4, '实际上课');
                $worksheet->setCellValueByColumnAndRow(5, 4, '缺少课时');
                // $worksheet->setCellValueByColumnAndRow(6, 3, '实际上课');
                // $worksheet->setCellValueByColumnAndRow(7, 3, '缺少课时');

                $worksheet->mergeCells('A1:J1'); // 合并第一行单元格
                $worksheet->mergeCells('A3:E3');
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
                $worksheet->getStyle('A3')->applyFromArray($title_array)->getFont()->setSize(12);
                $worksheet->getStyle('A2:B2')->applyFromArray($content_array)->getFont()->setSize(10);
                $worksheet->getStyle('A4:E4')->applyFromArray($title_array)->getFont()->setSize(12);

                // dump($all_teacher_durations[$key]);
                $i = 0;
                foreach ($all_teacher_durations[$key] as $m=>$td)
                {
                    // dump($all_teacher_durations[$key]);
                    // dump($td);
                    // dump($key);
                    if ($td != null)
                    {
                        $worksheet->setCellValueByColumnAndRow(1, 5+$i, $m);
                        $worksheet->setCellValueByColumnAndRow(2, 5+$i, $td[0]);
                        $worksheet->setCellValueByColumnAndRow(3, 5+$i, $td[1]);
                        $worksheet->setCellValueByColumnAndRow(4, 5+$i, $td[2]);
                        if ($td[3]>0)
                        {
                            $worksheet->setCellValueByColumnAndRow(5, 5+$i, $td[3]);
                        }
                        else
                        {
                            $worksheet->setCellValueByColumnAndRow(5, 5+$i, 0);
                        }
                        $i++;
                    }
                }
                $worksheet->setCellValueByColumnAndRow(1, 5+$i, '合计');
                $worksheet->setCellValueByColumnAndRow(2, 5+$i, $all_teacher_total_durations[$key][0]);
                $worksheet->setCellValueByColumnAndRow(3, 5+$i, $all_teacher_total_durations[$key][1]);
                $worksheet->setCellValueByColumnAndRow(4, 5+$i, $all_teacher_total_durations[$key][2]);
                if ($all_teacher_total_durations[$key][3]>0)
                {
                    $worksheet->setCellValueByColumnAndRow(5, 5+$i, $all_teacher_total_durations[$key][3]);
                }
                else
                {
                    $worksheet->setCellValueByColumnAndRow(5, 5+$i, 0);
                }


                if (count($all_teacher_extra_works[$key]) != 0)
                {
                    $worksheet->setCellValueByColumnAndRow(7, 3, '加班记录'); // G3
                    $worksheet->setCellValueByColumnAndRow(7, 4, '日期'); // G4
                    $worksheet->setCellValueByColumnAndRow(8, 4, '实际时长'); // H4
                    $worksheet->setCellValueByColumnAndRow(9, 4, '转换时长'); // I4
                    $worksheet->setCellValueByColumnAndRow(10, 4, '备注'); // J4

                    $worksheet->mergeCells('G3:J3');
                    $k = 0;
                    foreach ($all_teacher_extra_works[$key] as $atew)
                    {
                        $worksheet->setCellValueByColumnAndRow(7, 5+$k, date('Y-m-d', strtotime($atew->extra_work_start_time)));
                        $worksheet->setCellValueByColumnAndRow(8, 5+$k, ($atew->duration)*1.0);
                        if ($atew->extra_work_type == '带薪 1:1.2')
                        {
                            $worksheet->setCellValueByColumnAndRow(9, 5+$k, ($atew->duration)*1.2);
                            $worksheet->setCellValueByColumnAndRow(10, 5+$k, $atew->extra_work_type.',1.2倍抵课时');
                        }
                        else
                        {
                            $worksheet->setCellValueByColumnAndRow(9, 5+$k, ($atew->duration)*1.0);
                            $worksheet->setCellValueByColumnAndRow(10, 5+$k, $atew->extra_work_type.',1:1抵课时');
                        }
                        $k++;
                    }
                    $worksheet->setCellValueByColumnAndRow(7, 5+$k, '合计');
                    $worksheet->setCellValueByColumnAndRow(9, 5+$k, $all_teacher_extra_work_durations[$key]);
                    $worksheet->setCellValueByColumnAndRow(7, 5+$k+1, '缺少课时');
                    if (($all_teacher_total_durations[$key][3] - $all_teacher_extra_work_durations[$key])>0)
                    {
                        $worksheet->setCellValueByColumnAndRow(9, 5+$k+1, $all_teacher_total_durations[$key][3] - $all_teacher_extra_work_durations[$key]);
                    }
                    else
                    {
                        $worksheet->setCellValueByColumnAndRow(9, 5+$k+1, 0);
                    }
                    $worksheet->getStyle('G3')->applyFromArray($title_array)->getFont()->setSize(12);
                    $worksheet->getStyle('G4:J4')->applyFromArray($title_array)->getFont()->setSize(12);
                    $worksheet->getStyle('G5:J'.(5+$k+1))->applyFromArray($content_array)->getFont()->setSize(12);

                }

                $worksheet->getStyle('A5:E'.(5+$i))->applyFromArray($content_array)->getFont()->setSize(12);
                $worksheet->getStyle('A4:E'.(5+$i))->getAlignment()->setWrapText(true);
                $worksheet->getColumnDimension('A')->setWidth(15);
                $worksheet->getColumnDimension('B')->setWidth(15);
                $worksheet->getColumnDimension('C')->setWidth(15);
                $worksheet->getColumnDimension('D')->setWidth(15);
                $worksheet->getColumnDimension('E')->setWidth(15);
                $worksheet->getColumnDimension('G')->setWidth(15);
                $worksheet->getColumnDimension('H')->setWidth(15);
                $worksheet->getColumnDimension('I')->setWidth(15);
                $worksheet->getColumnDimension('J')->setWidth(15);
                $j++;
            }

        }
        // exit();


        // 下载
        $filename = $title.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
