<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Alter extends Model
{
    protected $table = 'alters';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * 导出换课记录
     * @param date $start_date
     * @param date $end_date
     * @param collection $alters
     * @param object $spreadsheet
     *
     * @return void
     */
    public static function export($start_date, $end_date, $alters, $spreadsheet)
    {
        $day_array = [0=>'日', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六'];
        $worksheet = $spreadsheet->getSheet(0);
        $title = $start_date.'~'.$end_date.' 换课信息汇总';
        $worksheet->setTitle('换课信息汇总');

        $worksheet->setCellValueByColumnAndRow(1, 1, '换课记录汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
        $worksheet->setCellValueByColumnAndRow(2, 2, $start_date.'~'.$end_date);
        $worksheet->setCellValueByColumnAndRow(1, 3, '调整项目');
        $worksheet->setCellValueByColumnAndRow(2, 3, '原上课日期');
        $worksheet->setCellValueByColumnAndRow(3, 3, '换课日期');
        $worksheet->setCellValueByColumnAndRow(4, 3, '周次');
        $worksheet->setCellValueByColumnAndRow(5, 3, '课程时间');
        $worksheet->setCellValueByColumnAndRow(6, 3, '级别');
        $worksheet->setCellValueByColumnAndRow(7, 3, '老师');
        $worksheet->setCellValueByColumnAndRow(8, 3, '课程标准时长'); // H
        $worksheet->setCellValueByColumnAndRow(9, 3, '创建日期');

        $worksheet->mergeCells('A1:H1'); // 合并第一行单元格
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
        $worksheet->getStyle('A3:I3')->applyFromArray($title_array)->getFont()->setSize(9);


        $count = $alters->count();
        foreach ($alters as $key => $a) {
            $worksheet->setCellValueByColumnAndRow(1, 4+$key, '课程时间调整');
            $worksheet->setCellValueByColumnAndRow(2, 4+$key, $a->lesson_date);
            $worksheet->setCellValueByColumnAndRow(3, 4+$key, $a->alter_date);
            $worksheet->setCellValueByColumnAndRow(4, 4+$key, $day_array[date('w',strtotime($a->lesson_date))]);
            $worksheet->setCellValueByColumnAndRow(5, 4+$key, $a->lesson->day.' '.date('H:i',strtotime($a->lesson->start_time)).'-'.date('H:i',strtotime($a->lesson->end_time)).'-'.$a->lesson->classroom);
            $worksheet->setCellValueByColumnAndRow(6, 4+$key, $a->lesson->lesson_name);
            $worksheet->setCellValueByColumnAndRow(7, 4+$key, $a->teacher->staff->englishname);
            $worksheet->setCellValueByColumnAndRow(8, 4+$key, $a->duration);
            $worksheet->setCellValueByColumnAndRow(9, 4+$key, date('Y-m-d', strtotime($a->created_at)));
        }
        $worksheet->getStyle('A4:I'.($count+3))->applyFromArray($content_array)->getFont()->setSize(9);
        $worksheet->getStyle('A3:I'.($count+3))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(15);
        $worksheet->getColumnDimension('B')->setWidth(15);
        $worksheet->getColumnDimension('C')->setWidth(15);
        $worksheet->getColumnDimension('D')->setWidth(5);
        $worksheet->getColumnDimension('E')->setWidth(20);
        $worksheet->getColumnDimension('F')->setWidth(10);
        $worksheet->getColumnDimension('I')->setWidth(15);
        // 下载
        $filename = $title.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }


}
