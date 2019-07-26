@extends('layouts.default')
@section('title','兼职员工个人信息')
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
        <th>入职日期</th>
    </tr>
    </thead>
       <tr>
            <td>{{ $staff->id }}</td>
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
            <td>{{ $staff->join_company }}</td>
</table>

@if ($staff->card != null)
@if ($staff->card->card_number != null)
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>工资卡号</th>
        <th>开户行</th>
    </tr>
    </thead>
    <tr>
        <td>{{ $staff->card->card_number}}</td>
        <td>{{ $staff->card->bank}}</td>
    </tr>
</table>
@endif
@endif

<table class="table table-bordered table-hover definewidth m10">
  <h4 style="margin-left: 20px; margin-right: 20px; margin-top: 20px; margin-bottom: 0px;">一周排班</h4>
    <thead>
    <tr>
        <th>星期</th>
        <th>应上班时间</th>
        <th>应下班时间</th>
    </tr>
    </thead>
    @foreach($staffworkdays as $workday)
    <tr>
        <td>周{{ $workday->workday_name }}</td>
        @if ($workday->work_time != null)
        <td>{{ date("H:i",strtotime($workday->work_time)) }}</td>
        @else
        <td>休息</td>
        @endif

        @if ($workday->home_time != null)
        <td>{{ date("H:i",strtotime($workday->home_time)) }}</td>
        @else
        <td>休息</td>
        @endif
    </tr>
    @endforeach
</table>
<p style="margin: 0px 20px; text-align: right;">本周应工作时长：{{ $total_duration }} 小时</p> <!-- 要考虑和午休没有交集，有交集要减去1小时 -->

<div style="margin: 20px">
  <a class="btn btn-primary"  href="{{ route('staffs.edit_part_time',array('id'=>$staff->id)) }}" role="button">编辑信息</a>
  <a class="btn btn-success" href="{{ route('staffs.part_time_index') }}" role="button">返回列表</a>
</div>


@stop
