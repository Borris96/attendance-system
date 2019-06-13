<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Staff;
use App\Holiday;
use App\ExtraWork;
use App\Absence;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Carbon;

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $staffs = Staff::where('status',true)->orderBy('id','asc')->paginate(15);
        return view('attendances/index',compact('staffs'));
    }

    public function show($id)
    {
        $staff = Staff::find($id);
        $attendances = $staff->attendances;
        return view('attendances.show',compact('staff','attendances'));
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
                //////目前读的只是第五个worksheet!
                for ($i = 5; $i<$num; $i++)
                {
                    $worksheet = $spreadsheet->getSheet($i); // 读取指定的sheet
                    $highest_row = $worksheet->getHighestRow(); // 总行数
                    $highest_column = $worksheet->getHighestColumn(); // 总列数
                    $highest_column_index = Coordinate::columnIndexFromString($highest_column);
                    $month_period = $worksheet->getCellByColumnAndRow(4,2)->getValue();
                    $this_month = explode('-', $month_period);
                    $year = $this_month[0];
                    $month = $this_month[1];
                    // 查询这个月的节假日调休，接下来使用这个集合进行遍历
                    $holidays = Holiday::where('date','<=',$year.'-'.$month.'-31')->where('date','>=',$year.'-'.$month.'-01')->get();
                    for ($c = 1; $c < $highest_column_index; $c += 15)
                    {
                        $englishname = $worksheet->getCellByColumnAndRow($c+9,3)->getValue();
                        $staff = Staff::where('status',true)->where('englishname',$englishname);
                        $staff_id = $staff->value('id');
                        // $staff_id = $worksheet->getCellByColumnAndRow($c+9,4)->getValue();
                        // dump($englishname);
                        // 如果这个人存在于在职员工数据库中，那么我才读取他的数据。这样可以避免读取没有必要的数据。
                        if (count($staff->get()) != 0)
                        {
                            $find = Attendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month); // 查询一下是否有该年该月的数据，防止导入重复的数据
                            if (count($find->get()) == 0)
                            {
                                for ($r = 12; $r <= $highest_row; $r++ )
                                {
                                    $attendance = new Attendance();
                                    $staff = Staff::find($staff_id);
                                    $attendance->staff_id = $staff->id;
                                    // 录入年月日
                                    $attendance->year = $year;
                                    $attendance->month = $month;

                                    $date_and_day = explode(' ', $worksheet->getCellByColumnAndRow($c,$r)->getValue());
                                    $date = $date_and_day[0];
                                    $day = $date_and_day[1];
                                    $attendance->date = $date;
                                    $attendance->day = $day;
                                    $ymd = $year.'-'.$month.'-'.$date;
                                    // 判断这一天是上班还是休息，录入该日期的类型
                                    if (count($holidays) != 0)
                                    {
                                        // 如果节假日调休管理中有特殊的日子，那么优先以其为准
                                        foreach ($holidays as $h) {
                                            if ($ymd == $h->date){
                                                $attendance->workday_type = $h->holiday_type;
                                            }
                                            else {
                                                // 否则寻找这一天是该员工休息日还是工作日
                                                $this_workday = $staff->staffworkdays->where('workday_name',$day);
                                                foreach ($this_workday as $twd) {
                                                    $attendance->should_work_time = $twd->work_time;
                                                    $attendance->should_home_time = $twd->home_time;
                                                }
                                                if (count($this_workday->where('work_time',!null)) != 0) { // work_time非null，上班
                                                    $attendance->workday_type = '上班';
                                                }
                                                else {
                                                    $attendance->workday_type = '休息';
                                                }
                                            }
                                        }
                                    }
                                    else {
                                            // 直接判断这一天是该员工休息日还是工作日
                                            $this_workday = $staff->staffworkdays->where('workday_name',$day);
                                            foreach ($this_workday as $twd) {
                                                $attendance->should_work_time = $twd->work_time;
                                                $attendance->should_home_time = $twd->home_time;
                                            }
                                            if (count($this_workday->where('work_time',!null)) != 0) { // work_time非null，上班
                                                $attendance->workday_type = '上班';
                                            }
                                            else {
                                                $attendance->workday_type = '休息';
                                            }
                                    }
                                    // 录入实际上下班时间
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
                                    $attendance->actual_work_time = $work_time;
                                    $attendance->actual_home_time = $home_time;

                                    // 计算迟到早退分钟数 以及 实际上班时长 判断是否异常
                                    if ($attendance->should_work_time != null && $attendance->should_home_time != null) {
                                        $swt = strtotime($attendance->should_work_time);
                                        $sht = strtotime($attendance->should_home_time);
                                        $attendance->should_duration = $attendance->calDuration($swt,$sht);
                                    }
                                    if ($attendance->actual_work_time != null && $attendance->actual_home_time != null)
                                    {
                                        $awt = strtotime($attendance->actual_work_time);
                                        $aht = strtotime($attendance->actual_home_time);
                                        $attendance->actual_duration = $attendance->calDuration($awt,$aht);
                                    }

                                    if ($attendance->should_work_time != null && $attendance->should_home_time != null && $attendance->actual_work_time != null && $attendance->actual_home_time != null)
                                    {
                                        $attendance->late_work = ($awt-$swt)/60; // 转换成分钟
                                        if (($awt-$swt)>0){ // 迟到是实际上班晚于应该上班
                                            // 后续还需要考虑到是否请假！！！！！
                                            if ($attendance->late_work > 15) //迟到15分钟以上算迟到
                                            {
                                                $attendance->is_late = true;
                                            }
                                            else {
                                                $attendance->is_late = false;
                                            }
                                        }
                                        else {
                                            $attendance->is_late = false;
                                            // dump($attendance->is_late);
                                        }
                                        // dump($sht);
                                        // dump($aht);
                                        // dump($sht-$aht);
                                        // exit();
                                        $attendance->early_home = ($sht-$aht)/60;
                                        if (($sht-$aht)>0){ // 早退是实际下班早于应该下班
                                            // 后续还需要考虑到是否请假！！！！！
                                            if ($attendance->early_home > 15) // 早退15分钟以上算早退
                                            {
                                                $attendance->is_early = true;
                                            }
                                            else {
                                                $attendance->is_early = false;
                                            }
                                        }
                                        else {
                                            $attendance->is_early = false;
                                        }

                                    }
                                    // 异常计算还需要获取当天加班、请假的时间，所以还是比较复杂的。 异常判断标准还需明确一下。
                                    $look_for_start_time = $ymd.' 00:00:00';
                                    $look_for_end_time = $ymd.' 24:00:00';
                                    // 查询当日被批准的加班记录

                                    // 目前这个查询方法只适用于查询结果只有一条的。如果多条结果不能如此直接赋值

                                    // 无论有没有批准都记录进去。
                                    $extra_work_id = ExtraWork::where('staff_id',$staff->id)->where('extra_work_start_time','>=',$look_for_start_time)->where('extra_work_end_time','<=',$look_for_end_time)->value('id');
                                    $attendance->extra_work_id = $extra_work_id;
                                    $attendance->save();
                                }
                            }
                            else {
                                session()->flash('danger','该员工该月记录已存在！');
                                return redirect()->back();
                            }
                        }
                    }
                }
            }
        }
        session()->flash('success','表格导入成功！');
        return redirect()->back();
    }
}
