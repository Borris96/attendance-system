<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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

    /**
     * 导出代课记录
     * @param date $start_date
     * @param date $end_date
     * @param collection $substitutes
     * @param object $spreadsheet
     *
     * @return void
     */
    public static function export($start_date, $end_date, $substitutes, $spreadsheet)
    {
        $day_array = [0=>'日', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六'];
        $worksheet = $spreadsheet->getSheet(0);
        $title = $start_date.'~'.$end_date.' 代课缺课信息汇总';
        $worksheet->setTitle('代课缺课信息汇总');

        $worksheet->setCellValueByColumnAndRow(1, 1, '代课缺课记录汇总表'); // (列，行)
        $worksheet->setCellValueByColumnAndRow(1, 2, '统计日期:');
        $worksheet->setCellValueByColumnAndRow(2, 2, $start_date.'~'.$end_date);
        $worksheet->setCellValueByColumnAndRow(1, 3, '调整项目');
        $worksheet->setCellValueByColumnAndRow(2, 3, '上课日期');
        $worksheet->setCellValueByColumnAndRow(3, 3, '周次');
        $worksheet->setCellValueByColumnAndRow(4, 3, '课程时间');
        $worksheet->setCellValueByColumnAndRow(5, 3, '级别');
        $worksheet->setCellValueByColumnAndRow(6, 3, '原上课老师');
        $worksheet->setCellValueByColumnAndRow(7, 3, '代课老师');
        $worksheet->setCellValueByColumnAndRow(8, 3, '课程标准时长');
        $worksheet->setCellValueByColumnAndRow(9, 3, '创建日期'); // I

        $worksheet->mergeCells('A1:I1'); // 合并第一行单元格
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


        $count = $substitutes->count();
        foreach ($substitutes as $key => $s) {
            if ($s->substitute_teacher_id != null)
            {
                $worksheet->setCellValueByColumnAndRow(1, 4+$key, '代课');
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(1, 4+$key, '缺课');
            }
            $worksheet->setCellValueByColumnAndRow(2, 4+$key, $s->lesson_date);
            $worksheet->setCellValueByColumnAndRow(3, 4+$key, $day_array[date('w',strtotime($s->lesson_date))]);
            $worksheet->setCellValueByColumnAndRow(4, 4+$key, $s->lesson->day.' '.date('H:i',strtotime($s->lesson->start_time)).'-'.date('H:i',strtotime($s->lesson->end_time)).'-'.$s->lesson->classroom);
            $worksheet->setCellValueByColumnAndRow(5, 4+$key, $s->lesson->lesson_name);
            $worksheet->setCellValueByColumnAndRow(6, 4+$key, $s->teacher->staff->englishname);
            if ($s->subTeacher != null)
            {
                $worksheet->setCellValueByColumnAndRow(7, 4+$key, $s->subTeacher->staff->englishname);
            }
            else
            {
                $worksheet->setCellValueByColumnAndRow(7, 4+$key, null);
            }
            $worksheet->setCellValueByColumnAndRow(8, 4+$key, $s->duration);
            $worksheet->setCellValueByColumnAndRow(9, 4+$key, date('Y-m-d', strtotime($s->created_at)));
        }
        $worksheet->getStyle('A4:I'.($count+3))->applyFromArray($content_array)->getFont()->setSize(9);
        $worksheet->getStyle('A3:I'.($count+3))->getAlignment()->setWrapText(true);
        $worksheet->getColumnDimension('A')->setWidth(10);
        $worksheet->getColumnDimension('B')->setWidth(15);
        $worksheet->getColumnDimension('C')->setWidth(5);
        $worksheet->getColumnDimension('D')->setWidth(20);
        $worksheet->getColumnDimension('E')->setWidth(10);
        $worksheet->getColumnDimension('F')->setWidth(10);
        $worksheet->getColumnDimension('G')->setWidth(10);
        $worksheet->getColumnDimension('H')->setWidth(15);
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
