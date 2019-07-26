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
        <th>总应</th>
        <th>总实</th>
        <th>总基本</th>
        <th>总额外</th>
        <th>总加班</th>
        <th>总请假</th>
        <th>总迟到</th>
        <th>总早退</th>
        <th>出勤天数</th>
        <th>工时差值</th>
        <th>总增补</th>
        <th>是否异常</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
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
            <td>{{ $ta->total_more_duration }}</td>
            <td>{{ $ta->total_lieu_work_duration }} /
              @if ($ta->total_extra_work_duration == $ta->total_lieu_work_duration)
              0.00
              @else
              {{$ta->total_salary_work_duration}}
              @endif
            </td>
            <td>{{ $ta->total_absence_duration }}</td>
            <td>{{ $ta->total_late_work }}分,{{ $ta->total_is_late }}次</td>
            <td>{{ $ta->total_early_home }}分,{{ $ta->total_is_early }}次</td>
            <td>应:{{ $ta->should_attend }}天/实:{{ $ta->actual_attend }}天</td>
            <td>{{ $ta->difference }}</td>
            <td>{{ $ta->total_add_duration }}</td>
            @if ($ta->abnormal == false)
            <td>否</td>
            @else
            <td style="color: red;">是</td>
            @endif
            <td>
                <a style="font-size: 12px;" href="{{ route('attendances.show',array($ta->id,'year'=>$year,'month'=>$month)) }}">查看</a>
            </td>
        </tr>
      @endforeach
      <tbody id="pageInfo">
</table>

@if (count($total_attendances)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif
<div style="margin: 20px">
  <a class="btn btn-success" href="{{ route('attendances.index') }}" role="button">返回考勤管理</a>
  &nbsp;&nbsp;
  <div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    导出考勤汇总 <span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">

    <form action="{{ route('attendances.export', array('year'=>$year,'month'=>$month,'option'=>'全职员工')) }}" method="POST" style="display: inline-block;">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">全职员工</button>
    </form>

    <form action="{{ route('attendances.export', array('year'=>$year,'month'=>$month,'option'=>'全职教师')) }}" method="POST" style="display: inline-block;">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">全职教师</button>
    </form>

    <form action="{{ route('attendances.export', array('year'=>$year,'month'=>$month,'option'=>'兼职批文')) }}" method="POST" style="display: inline-block;">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">兼职批文</button>
    </form>

    <form action="{{ route('attendances.export', array('year'=>$year,'month'=>$month,'option'=>'兼职助教')) }}" method="POST" style="display: inline-block;">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">兼职助教</button>
    </form>
    </ul>
  </div>



</div>



@stop
