<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Absence extends Model
{
    protected $table = 'absences';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function separateAbsences()
    {
        return $this->hasMany(SeparateAbsence::class);
    }

    /**
     *
     * 将一段请假时间每天时长作为数组返回
     *
     * @param time $first_day_home_time
     * @param time $last_day_work_time
     * @param datetime $absence_start_time
     * @param datetime $absence_end_time
     * @param array $duration_array
     * @return array $duration_array 若有多天，第一个元素为第一天时长，第二个元素为最后一天时长，完整天的时长等于当日工时（需要另读）
     */
    public function separateDuration($first_day_home_time, $last_day_work_time, $absence_start_time, $absence_end_time, $duration_array)
    {
        $start_time = strtotime($absence_start_time);
        $end_time = strtotime($absence_end_time);
        $start_date = date("Y-m-d", $start_time);
        $end_date = date("Y-m-d", $end_time);

        if ($end_time > $start_time)
        {
            if ($start_date == $end_date) // 请假起止在同一天
            {
                // time date('H:i',$start_time) 当日开始时间
                // time date('H:i',$end_time) 当日结束时间
                if (date('H:i',$start_time)>'13:00')
                {
                    $duration = ($end_time-$start_time)/(60*60);
                }
                elseif (date('H:i',$start_time)>='12:00' && date('H:i',$start_time)<='13:00')
                {
                    if (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $duration = 0;
                    }
                    elseif (date('H:i',$end_time)>'13:00')
                    $duration = ($end_time-strtotime($end_date.' 13:00'))/(60*60);
                }
                else
                {
                    if (date('H:i',$end_time)<'12:00')
                    {
                        $duration = ($end_time-$start_time)/(60*60);
                    }
                    elseif (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $duration = (strtotime($start_date.' 12:00')-$start_time)/(60*60);
                    }
                    else
                    {
                        $duration = ($end_time-$start_time)/(60*60)-1;
                    }
                }
                // 数组里只存一个时长
                $duration_array[] = $duration;
            }
            else // 请假起止不在同一天
            {
                // time date('H:i',$start_time) 当日开始时间
                // time $first_day_home_time 当日结束时间
                // 计算第一天小时数
                // if (date('H:i',$start_time)<'12:00')
                // {
                //     if ($first_day_home_time<='13:00')
                //     {
                //         $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                //     }
                //     else {
                //         $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                //     }
                // }
                // else
                // {
                //     $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                // }

                // time date('H:i',$start_time) 当日开始时间
                // time date('H:i',$end_time) 当日结束时间 $first_day_home_time

                // $end_time <=> strtotime($start_date.' '.$first_day_home_time)
                if (date('H:i',$start_time)>'13:00')
                {
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                }
                elseif (date('H:i',$start_time)>='12:00' && date('H:i',$start_time)<='13:00')
                {
                    if ($first_day_home_time>='12:00' && $first_day_home_time<='13:00')
                    {
                        $crest_start = 0;
                    }
                    elseif ($first_day_home_time>'13:00')
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-strtotime($start_date.' 13:00'))/(60*60);
                }
                else
                {
                    if ($first_day_home_time<'12:00')
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                    }
                    elseif ($first_day_home_time>='12:00' && $first_day_home_time<='13:00')
                    {
                        $crest_start = (strtotime($start_date.' 12:00')-$start_time)/(60*60);
                    }
                    else
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                    }

                }
                $duration_array[] = $crest_start;
                // 计算最后一天小时数
                // if ($last_day_work_time<'12:00')
                // {
                //     if (date('H:i',$end_time)<='13:00')
                //     {
                //         $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                //     }
                //     else {
                //         $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                //     }
                // }
                // else
                // {
                //     $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                // }



                // time date('H:i',$start_time) 当日开始时间 $last_day_work_time
                // time date('H:i',$end_time) 当日结束时间

                // $start_time <=> strtotime($end_date.' '.$last_day_work_time)
                if ($last_day_work_time>'13:00')
                {
                    $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                }
                elseif ($last_day_work_time>='12:00' && $last_day_work_time<='13:00')
                {
                    if (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = 0;
                    }
                    elseif (date('H:i',$end_time)>'13:00')
                    $crest_end = ($end_time-strtotime($end_date.' 13:00'))/(60*60);
                }
                else
                {
                    if (date('H:i',$end_time)<'12:00')
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    elseif (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = (strtotime($end_date.' 12:00')-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    else
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                    }
                }
                $duration_array[] = $crest_end;
            }
        }
        else
        {
            return false;
        }
        return $duration_array;
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $absence_start_time
     * @param timestamp $absence_end_time
     * @param timestamp $old_absence_start_time
     * @param timestamp $old_absence_end_time
     * @return boolean
     */

    public function isCrossing($absence_start_time, $absence_end_time, $old_absence_start_time, $old_absence_end_time)
    {
        if ($absence_end_time<=$old_absence_start_time || $old_absence_end_time<=$absence_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }

    /**
     * 导出请假记录
     * @param date $start_date
     * @param date $end_date
     * @param collection $absences
     * @param object $spreadsheet
     *
     * @return void
     */
    public static function export($start_date, $end_date, $absences, $spreadsheet)
    {
        $worksheet = $spreadsheet->getSheet(0);
        $title = $start_date.'~'.$end_date.' 请假信息汇总';
        $worksheet->setTitle('请假信息汇总');

        $worksheet->setCellValueByColumnAndRow(1, 1, '请假记录汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
        $worksheet->setCellValueByColumnAndRow(2, 2, $start_date.'~'.$end_date);
        $worksheet->setCellValueByColumnAndRow(1, 3, '编号');
        $worksheet->setCellValueByColumnAndRow(2, 3, '英文名');
        $worksheet->setCellValueByColumnAndRow(3, 3, '姓名');
        $worksheet->setCellValueByColumnAndRow(4, 3, '所属部门');
        $worksheet->setCellValueByColumnAndRow(5, 3, '请假类型');
        $worksheet->setCellValueByColumnAndRow(6, 3, '请假开始时间');
        $worksheet->setCellValueByColumnAndRow(7, 3, '请假结束时间');
        $worksheet->setCellValueByColumnAndRow(8, 3, '时长');
        $worksheet->setCellValueByColumnAndRow(9, 3, '是否批准');
        $worksheet->setCellValueByColumnAndRow(10, 3, '备注');

        $worksheet->mergeCells('A1:J1'); // 合并第一行单元格
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
        $worksheet->getStyle('A3:J3')->applyFromArray($title_array)->getFont()->setSize(9);


        $count = $absences->count();
        foreach ($absences as $key => $ab) {
            $worksheet->setCellValueByColumnAndRow(1, 4+$key, $ab->staff_id);
            $worksheet->setCellValueByColumnAndRow(2, 4+$key, $ab->staff->staffname);
            $worksheet->setCellValueByColumnAndRow(3, 4+$key, $ab->staff->englishname);
            $worksheet->setCellValueByColumnAndRow(4, 4+$key, $ab->staff->department_name);
            $worksheet->setCellValueByColumnAndRow(5, 4+$key, $ab->absence_type);
            $worksheet->setCellValueByColumnAndRow(6, 4+$key, $ab->absence_start_time);
            $worksheet->setCellValueByColumnAndRow(7, 4+$key, $ab->absence_end_time);
            $worksheet->setCellValueByColumnAndRow(8, 4+$key, $ab->duration);
            if ($ab->approve)
            {
                $worksheet->setCellValueByColumnAndRow(9, 4+$key, '是');
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(9, 4+$key, '否');
            }
            $worksheet->setCellValueByColumnAndRow(10, 4+$key, $ab->note);
        }
        $worksheet->getStyle('A4:J'.($count+3))->applyFromArray($content_array)->getFont()->setSize(9);
        $worksheet->getStyle('A3:J'.($count+3))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(10);
        $worksheet->getColumnDimension('B')->setWidth(10);
        $worksheet->getColumnDimension('C')->setWidth(10);
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
}
