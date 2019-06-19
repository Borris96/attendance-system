@extends('layouts.default')
@section('title','考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<h4 style="text-align: center">{{ $year }}年{{ $month }}月 考勤汇总</h4>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>职位</th>
        <th>总应工时</th>
        <th>总实际工时</th>
        <th>总基本工时</th>
        <th>总加班工时</th>
        <th>总迟到</th>
        <th>总早退</th>
        <th>总请假时长</th>
        <th>出勤天数</th>
        <th>工时差值</th>
        <th>是否异常</th>
        <th>当月工资</th>
        <th>操作</th>
    </tr>
    </thead>
      @foreach ($total_attendances as $ta)
       <tr>
            <td>{{ $ta->staff->id }}</td>
            <td>{{ $ta->staff->staffname }}</td>
            <td>{{ $ta->staff->englishname }}</td>
            <td>{{ $ta->staff->department_name }}</td>
            <td>{{ $ta->staff->position_name }}</td>
            <td>{{ $ta->total_should_duration }}</td>
            <td>{{ $ta->total_actual_duration }}</td>
            <td>{{ $ta->total_basic_duration }}</td>
            <td>{{ $ta->total_extra_work_duration }}</td>
            <td>{{ $ta->total_late_work }}分,{{ $ta->total_is_late }}次</td>
            <td>{{ $ta->total_early_home }}分,{{ $ta->total_is_early }}次</td>
            <td>{{ $ta->total_absence_duration }}</td>
            <td>应:{{ $ta->should_attend }}天/实:{{ $ta->actual_attend }}天</td>
            <td>{{ $ta->difference }}</td>
            @if ($ta->abnormal == false)
            <td>否</td>
            @else
            <td>是</td>
            @endif
            <td>还没算</td>
            <td>
                <a href="{{ route('attendances.show',$ta->id) }}">查看</a> <!-- route('staffs.edit', $staff->id) -->
            </td>
        </tr>
      @endforeach
</table>

{{ $total_attendances->links() }}

<div style="margin: 20px">
  <a class="btn btn-success" href="{{ route('attendances.index') }}" role="button">返回考勤管理</a>
</div>


@stop
