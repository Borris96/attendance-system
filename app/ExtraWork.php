<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExtraWork extends Model
{
    protected $table = 'extra_works';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    // public function attendance(){
    //     return $this->belongsTo(Attendance::class);
    // }

    // public function calDuration($extra_work_start_time, $extra_work_end_time){
    //     $str_start = strtotime($extra_work_start_time); // Convert it to string
    //     $str_end = strtotime($extra_work_end_time); // Convert it to sring
    //     if ($extra_work_end_time>$extra_work_start_time){
    //         $duration = ($str_end-$str_start)/(60*60); //Convert to hours
    //     } else {
    //         $duration=0;
    //     }
    //     return $duration;
    // }

    /**
     * 计算加班时长（小时）
     * @param timestamp $start_time,
     * @param timestamp $end_time
     * @return double
     */
    public function calDuration($start_time, $end_time)
    {
        $start_time = strtotime($start_time); // Convert it to string
        $end_time = strtotime($end_time);
        if ($end_time>$start_time) {
            // 只有当加班开始时间小于12点的时候会计算午饭时间 （不要轻易试探11:59这个临界值）
            if (date('H:i',$start_time)<'12:00')
            {
                // 12点到1点为午休时间，只要超过一点才离开就减去一小时午饭时间（不要轻易试探13:01这个值哦）
                if (date('H:i',$end_time)<='13:00')
                {
                    return ($end_time-$start_time)/(60*60);
                }
                else {
                    return ($end_time-$start_time)/(60*60)-1;
                }
            }
            else
            {
                return ($end_time-$start_time)/(60*60);
            }
        }
        else {
            return 0;
        }
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $ew_start_time
     * @param timestamp $ew_end_time
     * @param timestamp $old_ew_start_time
     * @param timestamp $old_ew_end_time
     * @return boolean
     */

    public function isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time)
    {
        if ($ew_end_time<=$old_ew_start_time || $old_ew_end_time<=$ew_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }

    /**
     * 导出加班记录
     * @param date $start_date
     * @param date $end_date
     * @param collection $extra_work
     * @param object $spreadsheet
     *
     * @return void
     */
    public static function export($start_date, $end_date, $extra_works, $spreadsheet)
    {
        $worksheet = $spreadsheet->getSheet(0);
        $title = $start_date.'~'.$end_date.' 加班信息汇总';
        $worksheet->setTitle('加班信息汇总');

        $worksheet->setCellValueByColumnAndRow(1, 1, '加班记录汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
        $worksheet->setCellValueByColumnAndRow(2, 2, $start_date.'~'.$end_date);
        $worksheet->setCellValueByColumnAndRow(1, 3, '编号');
        $worksheet->setCellValueByColumnAndRow(2, 3, '英文名');
        $worksheet->setCellValueByColumnAndRow(3, 3, '姓名');
        $worksheet->setCellValueByColumnAndRow(4, 3, '所属部门');
        $worksheet->setCellValueByColumnAndRow(5, 3, '加班类型');
        $worksheet->setCellValueByColumnAndRow(6, 3, '加班开始时间');
        $worksheet->setCellValueByColumnAndRow(7, 3, '加班结束时间');
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


        $count = $extra_works->count();
        foreach ($extra_works as $key => $ew) {
            $worksheet->setCellValueByColumnAndRow(1, 4+$key, $ew->staff_id);
            $worksheet->setCellValueByColumnAndRow(2, 4+$key, $ew->staff->staffname);
            $worksheet->setCellValueByColumnAndRow(3, 4+$key, $ew->staff->englishname);
            $worksheet->setCellValueByColumnAndRow(4, 4+$key, $ew->staff->department_name);
            $worksheet->setCellValueByColumnAndRow(5, 4+$key, $ew->extra_work_type);
            $worksheet->setCellValueByColumnAndRow(6, 4+$key, $ew->extra_work_start_time);
            $worksheet->setCellValueByColumnAndRow(7, 4+$key, $ew->extra_work_end_time);
            $worksheet->setCellValueByColumnAndRow(8, 4+$key, $ew->duration);
            if ($ew->approve)
            {
                $worksheet->setCellValueByColumnAndRow(9, 4+$key, '是');
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(9, 4+$key, '否');
            }
            $worksheet->setCellValueByColumnAndRow(10, 4+$key, $ew->note);
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
