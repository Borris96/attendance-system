<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attendance;
use App\Staff;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class TotalAttendance extends Model
{
    protected $table = 'total_attendances';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public static function calTotal($total_attendance, $attendances, $staff, $year, $month)
    {
        $total_attendance->staff_id = $staff->id;
        $total_attendance->department_id = $staff->department_id;
        $total_attendance->year = $year;
        $total_attendance->month = $month;

        $total_should_duration = 0;
        $total_actual_duration = 0;
        $total_is_late = 0;
        $total_is_early = 0;
        $total_late_work = 0;
        $total_early_home = 0;
        $should_attend = 0;
        $actual_attend = 0;
        $total_extra_work_duration = 0;
        $total_lieu_work_duration = 0;
        $total_absence_duration = 0;
        $total_basic_duration = 0;
        $total_more_duration = 0;
        // $total_abnormal = false;

        foreach ($attendances as $at)
        {
            if ($at->should_duration != null)
            {
                $total_should_duration += $at->should_duration;
                $should_attend += 1;
            }

            if ($at->actual_duration != null)
            {
                $total_actual_duration += $at->actual_duration;
                $actual_attend += 1;
            }
            $total_basic_duration += $at->basic_duration;

            // 不在应该上班时间上班了的时长(加班除外)
            if ($at->should_work_time == null && $at->should_home_time == null && $at->actual_work_time != null && $at->actual_home_time != null)
            {
                if ($at->extraWork == null)
                {
                    $total_more_duration += $at->actual_duration; //如果该日是加班,时长不算。
                }
            }

            $total_is_late += $at->is_late;
            $total_is_early += $at->is_early;
            if ($at->late_work>0 && $at->is_late == true) // 晚上班，且记作迟到才算在总迟到里
            {
                $total_late_work += $at->late_work;
            }

            if ($at->early_home>0 && $at->is_early == true) // 早下班，且记作早退才算在总迟到里
            {
                $total_early_home += $at->early_home;
            }

            // if ($at->abnormal == true)
            // {
            //     $total_abnormal = true;
            //     break;
            // }

            if ($at->extra_work_id != null)
            {
                $total_extra_work_duration += $at->extraWork->duration;
                if ($at->extraWork->extra_work_type == '调休')
                {
                    $total_lieu_work_duration += $at->extraWork->duration;
                }
            }

            if ($at->absence_id != null)
            {
                $total_absence_duration += $at->absence_duration;
            }
        }

        $total_abnormal = $attendances->where('abnormal',true);
        if (count($total_abnormal) == 0)
        {
            // 没有异常记录即不异常
            $total_attendance->abnormal = false;
        }
        else
        {
            $total_attendance->abnormal = true;
        }
        $total_attendance->total_should_duration = $total_should_duration;
        $total_attendance->total_actual_duration = $total_actual_duration;
        $total_attendance->total_is_late = $total_is_late;
        $total_attendance->total_is_early = $total_is_early;
        $total_attendance->total_late_work = $total_late_work;
        $total_attendance->total_early_home = $total_early_home;
        $total_attendance->should_attend = $should_attend;
        $total_attendance->actual_attend = $actual_attend;
        $total_attendance->total_extra_work_duration = $total_extra_work_duration;
        $total_attendance->total_absence_duration = $total_absence_duration;
        $total_attendance->total_basic_duration = $total_basic_duration;
        $total_attendance->total_more_duration = $total_more_duration;
        $total_attendance->difference = $total_attendance->total_basic_duration - $total_should_duration;
        $total_attendance->total_lieu_work_duration = $total_lieu_work_duration;
        $total_attendance->total_salary_work_duration = $total_extra_work_duration-$total_lieu_work_duration;
        $total_attendance->save();

        $total_attendance_id = $total_attendance->id;

        // 当该员工当月考勤汇总计算好之后，应当为当月每一天的考勤加入汇总数据的关联
        foreach ($attendances as $at)
        {
            $at->total_attendance_id = $total_attendance_id;
            $at->save();
        }
    }

    /**
     * 更新汇总记录
     * @param collection $this_month_attendance
     * @param object $attendance // 所修改的一条考勤记录
     * @param string $type // 决定用哪一种计算方式: add 增补工时; clock 补打卡
     *
     */
    public static function updateTotal($this_month_attendances, $attendance, $type='add')
    {
        // 查一下还有没有异常
        $this_month_abnormal = $this_month_attendances->where('abnormal',true);
        if (count($this_month_abnormal) == 0)
        {
            // 如果没有异常返回 false
            $attendance->totalAttendance->abnormal = false;
        }
        else
        {
            $attendance->totalAttendance->abnormal = true;
        }

        if ($type == 'clock' || $type == 'extra' || $type == 'absence')
        {
        $total_should_duration = 0;
        $total_actual_duration = 0;
        $total_extra_work_duration = 0;
        $total_absence_duration = 0;
        $total_basic_duration = 0;
        $total_lieu_work_duration = 0;
        $total_more_duration = 0;
        }

        $total_is_late = 0;
        $total_is_early = 0;
        $total_late_work = 0;
        $total_early_home = 0;
        $should_attend = 0;
        $actual_attend = 0;
        $total_add_duration = 0;

        foreach ($this_month_attendances as $at) {
            if ($type == 'clock' || $type == 'extra' || $type == 'absence')
            {
                if ($at->should_duration != null)
                {
                    $total_should_duration += $at->should_duration;
                    $should_attend += 1;
                }

                if ($at->actual_duration != null)
                {
                    $total_actual_duration += $at->actual_duration;
                    $actual_attend += 1;
                }

                if ($at->extra_work_id != null)
                {
                    $total_extra_work_duration += $at->extraWork->duration;
                    if ($at->extraWork->extra_work_type == '调休')
                    {
                        $total_lieu_work_duration += $at->extraWork->duration;
                    }
                }

                $total_basic_duration += $at->basic_duration;

                // 不在应该上班时间上班了的时长
                if ($at->should_work_time == null && $at->should_home_time == null && $at->actual_work_time != null && $at->actual_home_time != null)
                {
                    if ($at->extraWork == null)
                    {
                        $total_more_duration += $at->actual_duration; //如果该日是加班,时长不算。
                    }
                }

                if ($at->absence_id != null)
                {
                    $total_absence_duration += $at->absence_duration;
                }
            }

            // 录入总增补时间
            if ($at->add_duration != null)
            {
                $total_add_duration += $at->add_duration;
            }

            $total_is_late += $at->is_late;
            $total_is_early += $at->is_early;
            if ($at->late_work>0 && $at->is_late == true)
            {
                $total_late_work += $at->late_work;
            }

            if ($at->early_home>0 && $at->is_early == true)
            {
                $total_early_home += $at->early_home;
            }
        }

        // 好像和最前面的重复了
        $total_abnormal = $this_month_attendances->where('abnormal',true);
        if (count($total_abnormal) == 0)
        {
            // 没有异常记录即不异常
            $attendance->totalAttendance->abnormal = false;
        }
        else
        {
            $attendance->totalAttendance->abnormal = true;
        }

        if ($type == 'clock' || $type == 'extra' || $type == 'absence')
        {
            $attendance->totalAttendance->total_should_duration = $total_should_duration;
            $attendance->totalAttendance->total_actual_duration = $total_actual_duration;
            $attendance->totalAttendance->should_attend = $should_attend;
            $attendance->totalAttendance->actual_attend = $actual_attend;
            $attendance->totalAttendance->total_extra_work_duration = $total_extra_work_duration;
            $attendance->totalAttendance->total_absence_duration = $total_absence_duration;
            $attendance->totalAttendance->total_basic_duration = $total_basic_duration;
            $attendance->totalAttendance->difference = $attendance->totalAttendance->total_basic_duration - $total_should_duration;
            $attendance->totalAttendance->total_lieu_work_duration = $total_lieu_work_duration;
            $attendance->totalAttendance->total_salary_work_duration = $total_extra_work_duration-$total_lieu_work_duration;
            $attendance->totalAttendance->total_more_duration = $total_more_duration;
        }
        $attendance->totalAttendance->total_is_late = $total_is_late;
        $attendance->totalAttendance->total_is_early = $total_is_early;
        $attendance->totalAttendance->total_late_work = $total_late_work;
        $attendance->totalAttendance->total_early_home = $total_early_home;
        $attendance->totalAttendance->total_add_duration = $total_add_duration;
        $attendance->totalAttendance->save();

        if ($type == 'add')
        {
            session()->flash('success','补工时成功！');
        }
        elseif ($type == 'clock')
        {
            session()->flash('success','补打卡成功！');
        }
        elseif ($type == 'extra')
        {
            session()->flash('success','补加班成功！');
        }
        elseif ($type == 'absence')
        {
            session()->flash('success','补请假成功！');
        }
    }

    /**
     * 对每月汇总表进行导出，同时导出每个员工该月详细的考勤数据
     * @param object $spreadsheet
     * @param int $year
     * @param int $month
     * @return void
     */
    public static function exportTotal($spreadsheet, $year, $month)
    {
        $worksheet = $spreadsheet->getSheet(0);
        $title = $year.'_'.$month.' 考勤汇总';
        $worksheet->setTitle('考勤汇总');
        $worksheet->setCellValueByColumnAndRow(1, 1, '考勤汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
        $month_first_day = date('Y-m-01',strtotime($year.'-'.$month));
        $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
        $worksheet->setCellValueByColumnAndRow(2, 2, $month_first_day.'~'.$month_last_day);
        $worksheet->setCellValueByColumnAndRow(1, 3, '编号');
        $worksheet->setCellValueByColumnAndRow(2, 3, '英文名');
        $worksheet->setCellValueByColumnAndRow(3, 3, '姓名');
        $worksheet->setCellValueByColumnAndRow(4, 3, '所属部门');
        $worksheet->setCellValueByColumnAndRow(5, 3, '职位');
        $worksheet->setCellValueByColumnAndRow(6, 3, '总工作时长');
        $worksheet->setCellValueByColumnAndRow(6, 4, '总应');
        $worksheet->setCellValueByColumnAndRow(7, 4, '总实际');
        $worksheet->setCellValueByColumnAndRow(8, 4, '总基本');
        $worksheet->setCellValueByColumnAndRow(9, 4, '总额外');
        $worksheet->setCellValueByColumnAndRow(10, 3, '总加班时长');
        $worksheet->setCellValueByColumnAndRow(10, 4, '调休');
        $worksheet->setCellValueByColumnAndRow(11, 4, '带薪');
        $worksheet->setCellValueByColumnAndRow(12, 3, '总请假时长');
        $worksheet->setCellValueByColumnAndRow(13, 3, '总迟到');
        $worksheet->setCellValueByColumnAndRow(13, 4, '次数');
        $worksheet->setCellValueByColumnAndRow(14, 4, '分钟');
        $worksheet->setCellValueByColumnAndRow(15, 3, '总早退');
        $worksheet->setCellValueByColumnAndRow(15, 4, '次数');
        $worksheet->setCellValueByColumnAndRow(16, 4, '分钟');
        $worksheet->setCellValueByColumnAndRow(17, 3, '出勤天数');
        $worksheet->setCellValueByColumnAndRow(17, 4, '应');
        $worksheet->setCellValueByColumnAndRow(18, 4, '实');
        $worksheet->setCellValueByColumnAndRow(19, 3, '工时差值');
        $worksheet->setCellValueByColumnAndRow(20, 3, '总增补时长');

        $worksheet->mergeCells('A1:T1'); // 合并第一行单元格
        $worksheet->mergeCells('A3:A4');
        $worksheet->mergeCells('B3:B4');
        $worksheet->mergeCells('C3:C4');
        $worksheet->mergeCells('D3:D4');
        $worksheet->mergeCells('E3:E4');
        $worksheet->mergeCells('F3:I3'); // 合并"总工作时长"
        $worksheet->mergeCells('J3:K3');
        $worksheet->mergeCells('L3:L4');
        $worksheet->mergeCells('M3:N3');
        $worksheet->mergeCells('O3:P3');
        $worksheet->mergeCells('Q3:R3');
        $worksheet->mergeCells('S3:S4');
        $worksheet->mergeCells('T3:T4');


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

        //设置单元格样式
        $worksheet->getStyle('A1')->applyFromArray($title_array)->getFont()->setSize(24);
        $worksheet->getStyle('A2:B2')->applyFromArray($content_array)->getFont()->setSize(10);
        $worksheet->getStyle('A3:T4')->applyFromArray($title_array)->getFont()->setSize(9);

        // 导入员工汇总数据 (不支持单个员工的考勤汇总导出)
        $this_month_total_attendances = TotalAttendance::where('year',$year)->where('month',$month)->orderBy('department_id');
        $count = $this_month_total_attendances->count();
        foreach ($this_month_total_attendances->get() as $key => $tmta) {
            // 数据从第五行开始写入
            $worksheet->setCellValueByColumnAndRow(1, 5+$key, $tmta->staff_id);
            $worksheet->setCellValueByColumnAndRow(2, 5+$key, $tmta->staff->englishname);
            $worksheet->setCellValueByColumnAndRow(3, 5+$key, $tmta->staff->staffname);
            $worksheet->setCellValueByColumnAndRow(4, 5+$key, $tmta->staff->department_name);
            $worksheet->setCellValueByColumnAndRow(5, 5+$key, $tmta->staff->position_name);
            $worksheet->setCellValueByColumnAndRow(6, 5+$key, $tmta->total_should_duration);
            $worksheet->setCellValueByColumnAndRow(7, 5+$key, $tmta->total_actual_duration);
            $worksheet->setCellValueByColumnAndRow(8, 5+$key, $tmta->total_basic_duration);
            $worksheet->setCellValueByColumnAndRow(9, 5+$key, $tmta->total_more_duration);
            $worksheet->setCellValueByColumnAndRow(10, 5+$key, $tmta->total_lieu_work_duration);
            $worksheet->setCellValueByColumnAndRow(11, 5+$key, $tmta->total_salary_work_duration);
            $worksheet->setCellValueByColumnAndRow(12, 5+$key, $tmta->total_absence_duration);
            $worksheet->setCellValueByColumnAndRow(13, 5+$key, $tmta->total_is_late);
            $worksheet->setCellValueByColumnAndRow(14, 5+$key, $tmta->total_late_work);
            $worksheet->setCellValueByColumnAndRow(15, 5+$key, $tmta->total_is_early);
            $worksheet->setCellValueByColumnAndRow(16, 5+$key, $tmta->total_early_home);
            $worksheet->setCellValueByColumnAndRow(17, 5+$key, $tmta->should_attend);
            $worksheet->setCellValueByColumnAndRow(18, 5+$key, $tmta->actual_attend);
            $worksheet->setCellValueByColumnAndRow(19, 5+$key, $tmta->difference);
            if ($tmta->total_add_duration == null)
            {
                $worksheet->setCellValueByColumnAndRow(20, 5+$key, 0);
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(20, 5+$key, $tmta->total_add_duration);
            }
        }
        // 设置数据样式
        $worksheet->getStyle('A5:T'.($count+4))->applyFromArray($content_array)->getFont()->setSize(9);
        $worksheet->getStyle('A3:T'.($count+4))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(10);
        $worksheet->getColumnDimension('B')->setWidth(10);
        $worksheet->getColumnDimension('C')->setWidth(10);
        // 录入每个员工的详细考勤
        foreach ($this_month_total_attendances->get() as $key => $tmta)
        {
            $staff = $tmta->staff;
            $staffsheet = $spreadsheet->createSheet($key+1);
            $staffsheet->setTitle($staff->englishname);
            $staffsheet->setCellValueByColumnAndRow(1, 1, '员工每日考勤');
            $staffsheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
            $staffsheet->setCellValueByColumnAndRow(2, 2, $month_first_day.'~'.$month_last_day);
            $staffsheet->setCellValueByColumnAndRow(1,3, '员工编号');
            $staffsheet->setCellValueByColumnAndRow(1,4, $staff->id);
            $staffsheet->setCellValueByColumnAndRow(2,3, '员工英文名');
            $staffsheet->setCellValueByColumnAndRow(2,4, $staff->englishname);
            $staffsheet->setCellValueByColumnAndRow(3,3, '员工姓名');
            $staffsheet->setCellValueByColumnAndRow(3,4, $staff->staffname);
            $staffsheet->setCellValueByColumnAndRow(4,3, '所属部门');
            $staffsheet->setCellValueByColumnAndRow(4,4, $staff->department_name);
            $staffsheet->setCellValueByColumnAndRow(5,3, '所属部门');
            $staffsheet->setCellValueByColumnAndRow(5,4, $staff->position_name);

            $staffsheet->setCellValueByColumnAndRow(1,6, '类型');
            $staffsheet->setCellValueByColumnAndRow(2,6, '日期');
            $staffsheet->setCellValueByColumnAndRow(3,6, '星期');
            $staffsheet->setCellValueByColumnAndRow(4,6, '应上班');
            $staffsheet->setCellValueByColumnAndRow(5,6, '应下班');
            $staffsheet->setCellValueByColumnAndRow(6,6, '实上班');
            $staffsheet->setCellValueByColumnAndRow(7,6, '实下班');
            $staffsheet->setCellValueByColumnAndRow(8,6, '迟到(分)');
            $staffsheet->setCellValueByColumnAndRow(9,6, '早退(分)');
            $staffsheet->setCellValueByColumnAndRow(10,6, '请假记录');
            $staffsheet->setCellValueByColumnAndRow(10,7, '类型');
            $staffsheet->setCellValueByColumnAndRow(11,7, '请假时间');
            $staffsheet->setCellValueByColumnAndRow(12,7, '时长');
            $staffsheet->setCellValueByColumnAndRow(13,6, '加班记录');
            $staffsheet->setCellValueByColumnAndRow(13,7, '类型');
            $staffsheet->setCellValueByColumnAndRow(14,7, '加班时间');
            $staffsheet->setCellValueByColumnAndRow(15,7, '时长');
            $staffsheet->setCellValueByColumnAndRow(16,6, '工时');
            $staffsheet->setCellValueByColumnAndRow(16,7, '应工时');
            $staffsheet->setCellValueByColumnAndRow(17,7, '实工时');
            $staffsheet->setCellValueByColumnAndRow(18,7, '基本工时');
            $staffsheet->setCellValueByColumnAndRow(19,6, '是否异常');
            $staffsheet->setCellValueByColumnAndRow(20,6, '增补记录');
            $staffsheet->setCellValueByColumnAndRow(20,7, '原因');
            $staffsheet->setCellValueByColumnAndRow(21,7, '增补时间');
            $staffsheet->setCellValueByColumnAndRow(22,7, '时长');

            $staffsheet->mergeCells('A1:V1');
            $staffsheet->mergeCells('A6:A7');
            $staffsheet->mergeCells('B6:B7');
            $staffsheet->mergeCells('C6:C7');
            $staffsheet->mergeCells('D6:D7');
            $staffsheet->mergeCells('E6:E7');
            $staffsheet->mergeCells('F6:F7');
            $staffsheet->mergeCells('G6:G7');
            $staffsheet->mergeCells('H6:H7');
            $staffsheet->mergeCells('I6:I7');
            $staffsheet->mergeCells('J6:L6');
            $staffsheet->mergeCells('M6:O6');
            $staffsheet->mergeCells('P6:R6');
            $staffsheet->mergeCells('S6:S7');
            $staffsheet->mergeCells('T6:V6');

            $staffsheet->getStyle('A1')->applyFromArray($title_array)->getFont()->setSize(24);
            $staffsheet->getStyle('A2:B2')->applyFromArray($content_array)->getFont()->setSize(10);
            $staffsheet->getStyle('A3:E3')->applyFromArray($title_array)->getFont()->setSize(9);
            $staffsheet->getStyle('A4:E4')->applyFromArray($content_array)->getFont()->setSize(9);
            $staffsheet->getStyle('A6:V7')->applyFromArray($title_array)->getFont()->setSize(9);

            $count = $tmta->attendances->count();
            // 从该员工每月汇总中取出每日考勤
            foreach ($tmta->attendances as $key => $at) {
                $staffsheet->setCellValueByColumnAndRow(1, 8+$key, $at->workday_type);
                $staffsheet->setCellValueByColumnAndRow(2, 8+$key, $month.'/'.$at->date);
                $staffsheet->setCellValueByColumnAndRow(3, 8+$key, $at->day);
                $staffsheet->setCellValueByColumnAndRow(4, 8+$key, date('H:i', strtotime($at->should_work_time)));
                $staffsheet->setCellValueByColumnAndRow(5, 8+$key, date('H:i', strtotime($at->should_home_time)));
                $staffsheet->setCellValueByColumnAndRow(6, 8+$key, date('H:i', strtotime($at->actual_work_time)));
                $staffsheet->setCellValueByColumnAndRow(7, 8+$key, date('H:i', strtotime($at->actual_home_time)));
                $staffsheet->setCellValueByColumnAndRow(8, 8+$key, $at->late_work);
                $staffsheet->setCellValueByColumnAndRow(9, 8+$key, $at->early_home);

                if ($at->absence_id != null)
                {
                    $staffsheet->setCellValueByColumnAndRow(10, 8+$key, $at->absence->absence_type);
                    $staffsheet->setCellValueByColumnAndRow(11, 8+$key, date('m-d H:i', strtotime($at->absence->absence_start_time)).'~'.date('m-d H:i', strtotime($at->absence->absence_end_time)));
                    $staffsheet->setCellValueByColumnAndRow(12, 8+$key, $at->absence_duration);
                }
                if ($at->extra_work_id != null)
                {
                    $staffsheet->setCellValueByColumnAndRow(13, 8+$key, $at->extraWork->extra_work_type);
                    $staffsheet->setCellValueByColumnAndRow(14, 8+$key, date('H:i', strtotime($at->extraWork->extra_work_start_time)).'~'.date('H:i', strtotime($at->extraWork->extra_work_end_time)));
                    $staffsheet->setCellValueByColumnAndRow(15, 8+$key, $at->extraWork->duration);
                }

                $staffsheet->setCellValueByColumnAndRow(16, 8+$key, $at->should_duration);
                $staffsheet->setCellValueByColumnAndRow(17, 8+$key, $at->actual_duration);
                $staffsheet->setCellValueByColumnAndRow(18, 8+$key, $at->basic_duration);
                if ($at->abnormal == false)
                {
                    $staffsheet->setCellValueByColumnAndRow(19, 8+$key, '否');
                }
                else
                {
                    $staffsheet->setCellValueByColumnAndRow(19, 8+$key, '是');
                }
                if ($at->addTimes != null)
                {
                    $reason = '';
                    $time = '';
                    foreach ($at->addTimes as $k=>$ad) {
                        $reason .= ($k+1).'.'.$ad->reason.' ';
                        $time .= date('H:i', strtotime($ad->add_start_time)).'~'.date('H:i', strtotime($ad->add_end_time))."\n";
                    }
                    $staffsheet->setCellValueByColumnAndRow(20, 8+$key, $reason);
                    $staffsheet->setCellValueByColumnAndRow(21, 8+$key, $time);
                    $staffsheet->setCellValueByColumnAndRow(22, 8+$key, $at->add_duration);
                }
            }
            $staffsheet->getStyle('A8:V'.($count+7))->applyFromArray($content_array)->getFont()->setSize(9);
            $staffsheet->getStyle('A6:V'.($count+7))->getAlignment()->setWrapText(true);
            $staffsheet->getColumnDimension('K')->setAutoSize(true);
            $staffsheet->getColumnDimension('N')->setAutoSize(true);
            $staffsheet->getColumnDimension('U')->setAutoSize(true);
        }
        // 下载
        $filename = $title.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
