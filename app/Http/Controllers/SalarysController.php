<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salary;

class SalarysController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $salarys = Salary::orderBy('id','asc')->paginate(50);
        return view('salarys/index',compact('salarys'));
    }

    public function create()
    {
        return view('salarys/create');
    }

    public function destroy($id)
    {
        $salary = Salary::find($id);
        $salary->delete();
        session()->flash('success', '成功删除时薪记录！');
        return back();
    }

    public function edit($id)
    {
        $salary = Salary::find($id);
        return view('salarys.edit',compact('salary'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'salary_type'=>'required|unique:salarys',
            'salary'=>'required|max:3',
            'note'=>'max:140',
        ]);

        $salary = new salary();
        $salary->salary = $request->salary;
        $salary->salary_type = $request->salary_type;
        $salary->note = $request->note;

        //薪资创建重复检测？？

        if ($salary->save()) {
            session()->flash('success','保存成功！');
            return redirect('salarys'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'salary_type'=>'required|unique:salarys,salary_type,'.$id.'|max:50',
            'salary'=>'required',
            'note'=>'max:140',
        ]);

        $salary = Salary::find($id);
        $salary->salary = $request->salary;
        $salary->salary_type = $request->salary_type;
        $salary->note = $request->note;

        //薪资创建重复检测？？

        if ($salary->save()) {
            session()->flash('success','更新成功！');
            return redirect('salarys'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
