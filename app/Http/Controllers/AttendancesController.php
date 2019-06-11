<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('attendances/index');
    }

    public function showf()
    {
        return view('attendances/showf');
    }

    public function show()
    {

    }
    /**
     * 将上传的表格导入数据库
     *
     * @return str $filename
     */
    public function import(Request $request)
    {
         $this->validate($request, [
          'select_file'  => 'required|mimes:xls,xlsx'
         ]);

        if ($request->isMethod('POST')){
            $file = $request->file('select_file');
            // 判断文件是否上传成功
            if ($file->isValid()){
                // 原文件名
                // $originalName = $file->getClientOriginalName();
                // 扩展名
                $ext = $file->getClientOriginalExtension();
                // 临时绝对路径
                $realPath = $file->getRealPath();
                // MimeType 这是 HTTP 标准中该资源的媒体类型
                // $type = $file->getClientMimeType();
                // 判断是哪种表格格式
                if ($ext == 'xlsx') {
                    $reader = new Xlsx();
                } elseif ($ext == 'xls') {
                    $reader = new Xls();
                } else {
                    session()->flash('danger','文件格式错误！');
                    redirect()->back();
                }
                $reader->setReadDataOnly(TRUE); // 只读
                $spreadsheet = $reader->load($realPath);
                $num = $spreadsheet->getSheetCount(); // Sheet的总数
                $worksheet = $spreadsheet->getSheet(5); // 读取指定的sheet
                $highest_row = $worksheet->getHighestRow(); // 总行数
                $highest_column = $worksheet->getHighestColumn(); // 总列数
                $highest_column_index = Coordinate::columnIndexFromString($highest_column);
                $month_period = $worksheet->getCellByColumnAndRow(4,2)->getValue();
                $this_month = explode('-', $month_period);
                $year = $this_month[0];
                $month = $this_month[1];
                for ($c = 1; $c < $highest_column_index; $c += 15)
                {
                    $englishname = $worksheet->getCellByColumnAndRow($c+9,3)->getValue();
                    $staff_id = $worksheet->getCellByColumnAndRow($c+9,4)->getValue();
                    dump($englishname);
                    dump($staff_id);
                    // 后续工作：如果这个人存在于在职员工数据库中，那么我才读取他的数据。这样可以避免读取没有必要的数据。

                    for ($r = 12; $r <= $highest_row; $r++ )
                    {
                        $date_and_day = explode(' ', $worksheet->getCellByColumnAndRow($c,$r)->getValue());
                        $date = $date_and_day[0];
                        $day = $date_and_day[1];
                        // 默认工作日休息日读取的列不同
                        if ($day == '日' || $day == '六') {
                            $work_time = $worksheet->getCellByColumnAndRow($c+10,$r)->getValue();
                            $home_time = $worksheet->getCellByColumnAndRow($c+12,$r)->getValue();
                        }
                        else {
                            $work_time = $worksheet->getCellByColumnAndRow($c+1,$r)->getValue();
                            if ($work_time == '旷工') {
                                $work_time = null;
                            }
                            $home_time = $worksheet->getCellByColumnAndRow($c+3,$r)->getValue();
                        }
                        dump($date);
                        dump($work_time);
                        dump($home_time);
                    }
                }


                dump($year);
                dump($month);
                dump($num);
                // dump($worksheet);
                // dump($highest_row);
                // dump($highest_column);
                // dump($highest_column_index);
                exit();
            }
        }
    }
}
