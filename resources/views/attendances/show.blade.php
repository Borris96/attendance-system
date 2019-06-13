@extends('layouts.default')
@section('title','员工个人考勤信息')
@section('content')
@include('shared._messages')
<h4 style="margin: 20px;">员工个人考勤信息</h4>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>职位</th>
    </tr>
    </thead>
      <tr>
        <td>{{ $staff->id }}</td>
        <td><b>{{ $staff->staffname }}</b></td>
        <td><b>{{ $staff->englishname }}</b></td>
        <td>{{ $staff->department_name }}</td>
        <td>{{ $staff->position_name}}</td>
      </tr>
</table>


<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>类型</th>
        <th>日期</th>
        <th>星期</th>
        <th>应上班</th>
        <th>应下班</th>
        <th>实上班</th>
        <th>实下班</th>
        <th>迟到（分）</th>
        <th>早退（分）</th>
        <th>请假记录</th>
        <th>加班记录</th>
        <th>应总工时</th>
        <th>实际总工时</th>
        <th>是否异常</th>
        <th>操作</th>
    </tr>
    </thead>
      @foreach ($attendances as $attendance)
       <tr>
            <td>{{ $attendance->workday_type }}</td>
            <td>{{ $attendance->year }}-{{ $attendance->month }}-{{ $attendance->date }}</td>
            <td>{{ $attendance->day }}</td>
            <td>{{ $attendance->should_work_time }}</td>
            <td>{{ $attendance->should_home_time }}</td>
            <td>{{ $attendance->actual_work_time }}</td>
            <td>{{ $attendance->actual_home_time }}</td>
            @if ($attendance->late_work>0)
            <td>{{ $attendance->late_work }}</td>
            @else
            <td>0.00</td>
            @endif
            @if ($attendance->early_home>0)
            <td>{{ $attendance->early_home }}</td>
            @else
            <td>0.00</td>
            @endif
            <td>事假，16:00-18:00</td>
            @if ($attendance->extraWork != null)
            <td>{{ $attendance->extraWork->extra_work_type }},
              {{ date("H:i", strtotime($attendance->extraWork->extra_work_start_time)) }}-
              {{ date("H:i", strtotime($attendance->extraWork->extra_work_end_time)) }},
              @if ($attendance->extraWork->approve)
              批准
              @else
              未批准
              @endif
            @else
            <td>无</td>
            @endif
            </td>
            <td>{{ $attendance->should_duration }}</td>
            <td>{{ $attendance->actual_duration }}</td>
            <td>否</td>
            <td>
                <a href="">编辑工时</a> | <!-- route('staffs.edit', $staff->id) -->
                <a href="">编辑请假</a> |
                <a href="">编辑加班</a>
            </td>
        </tr>
      @endforeach
</table>

<h5 style="margin: 20px;">工资详情</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>基本工时</th>
        <th>基本时薪</th>
        <th>特殊工时</th>
        <th>特殊时薪</th>
        <th>扣费</th>
        <th>该月工资</th>
    </tr>
    </thead>
       <tr>
            <td>100小时</td>
            <td>30元</td>
            <td>20小时</td>
            <td>50元</td>
            <td>100元</td>
            <td>1000元</td>
</table>

@stop
