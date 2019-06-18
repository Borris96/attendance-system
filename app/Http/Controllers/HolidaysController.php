<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holiday;

class HolidaysController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $holidays = Holiday::orderBy('date','desc')->paginate(10);
        return view('holidays/index',compact('holidays'));
    }

    public function destroy($id)
    {
        $holiday = Holiday::find($id);
        $holiday->delete();
        session()->flash('success', '成功删除节假日调休记录！');
        return back();
    }

    public function create()
    {
        $workdays = ['日','一','二','三','四','五','六'];
        return view('holidays/create',compact('workdays'));
    }

    public function edit($id){
        $holiday = Holiday::find($id);
        $workdays = ['日','一','二','三','四','五','六'];
        return view('holidays.edit',compact('holiday','workdays'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'date'=>'required|unique:holidays',
            'holiday_type'=>'required',
            'note'=>'required|max:140',
        ]);

        $holiday = new Holiday();
        $holiday->date = $request->date;
        $holiday->holiday_type = $request->holiday_type;

        if ($holiday->holiday_type == '上班')
        {
            $holiday->workday_name = $request->workday;
            if ($holiday->workday_name == null)
            {
                session()->flash('warning','请填写调上周几的班！');
                return redirect()->back()->withInput();
            }
        }
        $holiday->note = $request->note;

        //日期重复检测 -> 已经被表单验证代替了
        // $current_date = strtotime($holiday->date);
        // $holidays = Holiday::all(); //这个要改，只需遍历当年的日期
        // foreach ($holidays as $h) {
        //     $old_date = strtotime($h->date);
        //     if ($holiday->isRepeat($current_date, $old_date) == true)
        //     {
        //         session()->flash('danger','时间已存在！');
        //         return redirect()->back()->withInput();
        //     }
        // }

        if ($holiday->save()) {
            session()->flash('success','保存成功！');
            return redirect('holidays'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date'=>'required|unique:holidays,date,'.$id,
            'holiday_type'=>'required',
            'note'=>'required|max:140',
        ]);

        $holiday = Holiday::find($id);
        $holiday->date = $request->date;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->note = $request->note;

        if ($holiday->holiday_type == '上班')
        {
            $holiday->workday_name = $request->workday;
            if ($holiday->workday_name == null)
            {
                session()->flash('warning','请填写调上周几的班！');
                return redirect()->back()->withInput();
            }
        }

        //日期重复检测 ->已经用表单验证代替了
        // $current_date = strtotime($holiday->date);
        // $holidays = Holiday::all()->whereNotIn('id',[$id]); //这个要改，只需遍历当年的日期
        // foreach ($holidays as $h) {
        //     $old_date = strtotime($h->date);
        //     if ($holiday->isRepeat($current_date, $old_date) == true)
        //     {
        //         session()->flash('danger','时间已存在！');
        //         return redirect()->back()->withInput();
        //     }
        // }

        if ($holiday->save()) {
            session()->flash('success','更新成功！');
            return redirect('holidays'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }

    }
}
