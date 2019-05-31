@extends('layouts.default')
@section('title','员工个人信息')
@section('content')
<h4 style="margin: 20px;">员工个人信息 - {{ $staff->staffname }}</h4>
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
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
</table>


<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>年假天数</th>
        <th>剩余天数</th>
    </tr>
    </thead>
    <tr>
        <td>{{ $staff->annual_holiday}}</td>
        <td>{{ $staff->annual_holiday - $staff->remaining_holiday}}</td> <!-- 请假管理功能完善之后需要修改 -->
    </tr>
</table>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>星期</th>
        <th>应上班时间</th>
        <th>应下班时间</th>
    </tr>
    </thead>
    @foreach($workdays as $workday)
    <tr>
        <td>{{ $workday->workday_name }}</td>
        <td>{{ date("H:i",strtotime($staff->work_time)) }}</td>
        <td>{{ date("H:i",strtotime($staff->home_time)) }}</td>
    </tr>
    @endforeach
</table>
<p style="margin: 20px; text-align: right;">总应工作时长：48小时</p>
<a class="btn btn-success" style="margin: 20px" href="{{ route('staffs.index') }}" role="button">返回列表</a>


@stop
