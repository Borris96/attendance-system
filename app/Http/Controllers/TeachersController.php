<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\Staff;
use App\Lesson;
use App\Term;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $terms = Term::all();
        $teachers = Teacher::where('status',true)->get();
        $staffs = Staff::where('status',true)->where('teacher_id',null)->orderBy('id','asc')->get();
        // $staffs = Staff::where('status',true)->orderBy('id','asc')->get();
        return view('teachers/index',compact('staffs','teachers','terms'));
    }

    public function show($id)
    {
        // 显示这个学期的排课。 目前还没有从用户处获取当前学期的方法。
        $current_term_id = 1;
        $term = Term::find($current_term_id);
        $teacher = Teacher::find($id);
        $lessons = Lesson::where('teacher_id',$id)->where('term_id',1)->orderBy('day','asc')->get();
        return view('teachers/show',compact('teacher','lessons','term'));
    }

    public function edit($id)
    {
        $teacher = Teacher::find($id);
        $lessons = Lesson::whereNull('teacher_id')->get();
        return view('teachers/edit',compact('lessons','teacher'));
    }

    // 新增老师(从员工中选择)
    public function role(Request $request)
    {
        // $this->validate($request, [
        //     'id'=>'integer|required|unique:staffs',
        // ]);
        $staff_ids = $request->input('staff_ids');
        foreach($staff_ids as $staff_id)
        {
            $staff = Staff::find($staff_id);
            // 如果之前没当过老师，新建；如果之前当过，找到之前的老师id，重新建立关联
            if ($staff->teacher==null)
            {
                $teacher = new Teacher();
            }
            else // 虽然下面代码写了，但是暂时不考虑重新回来当老师的情况
            {
                $teacher = Teacher::find($staff->teacher_id);
            }
            // 目前新增老师的操作是将老师id和员工id建立关联
            $teacher->staff_id = $staff_id;
            $teacher->status = true;
            if ($teacher->save())
            {
                $staff->teacher_id = $teacher->id;
                $staff->save();
                session()->flash('success','添加老师成功！');
            }
        }
        return redirect()->back();
    }

    public function remove($id)
    {
        $teacher = Teacher::find($id);
        // 移除老师操作，目前是移除老师id和员工id的关联
        $teacher->status = false;
        // $teacher->staff->teacher_id = null;
        $teacher->save();
        session()->flash('warning','移除老师成功！');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'lesson_id'=>'required',
        ]);
        $lesson_ids = $request->input('lesson_id');
        foreach($lesson_ids as $id)
        {
            $lesson = Lesson::find($id);
            $lesson->teacher_id = $request->get('teacher_id');
            $lesson->save();
        }
        session()->flash('success','关联课程成功！');
        return redirect('teachers');
    }
}
