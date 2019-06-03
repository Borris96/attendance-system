<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Absence;

class AbsencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('absences/index');
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('absences/create',compact('staffs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'staff_id'=>'required',
            'absence_type'=>'required|max:50',
            'absence_start_time'=>'required',
            'absence_end_time'=>'required:50',
            'approve' => 'required',
            'note'=>'max:140',
        ]);

        $absence = new Absence();
        $absence->staff_id = $request->get('staff_id');
        $absence->absence_type = $request->get('absence_type');
        $absence->absence_start_time = $request->get('absence_start_time');
        $absence->absence_end_time = $request->get('absence_end_time');

        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');
        // $absence->duration = 0.9;
        $absence->duration = $absence->calDuration($absence->absence_start_time, $absence->absence_end_time);

        // $now = $staff->absences()->value('duration'); //取在staffscontroller里duration的值，此处用不上。
        // dump($staff);
        // exit();

        // 只有年假情况remaining
        if ($absence->absence_type == "年假" && $absence->approve == true){
            $staff = Staff::find($absence->staff_id);
            $remaining = $staff->remaining_annual_holiday;
            $staff->remaining_annual_holiday = $remaining - $absence->duration;
            if (!($staff->save())){
                session()->flash('danger','年假更新失败！');
            }
        }

        if ($absence->save()) {
            session()->flash('success','保存成功！');
            return redirect('absences'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }
    }
}
