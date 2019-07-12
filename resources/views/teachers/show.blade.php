@extends('layouts.default')
@section('title','老师信息')
@section('content')
<h4 style="margin: 20px;">{{$teacher->staff->englishname}}</h4>

<h5 style="margin: 20px;">上课安排 - {{$term->term_name}}</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>课程名称</th>
        <th>上课时间</th>
        <th>教室</th>
        <th>时长</th>
    </tr>
    </thead>
    @foreach ($lessons as $l)
    <tr>
      <td>{{ $l->lesson_name }}</td>
      <td>{{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}</td>
      <td>{{$l->classroom}}</td>
      <td>{{$l->duration}}</td>
    </tr>
    @endforeach
</table>

<h5 style="margin: 20px;">每月时长</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>月份</th>
        <th>周五</th>
        <th>周六</th>
        <th>周日</th>
        <th>实际排课</th>
        <th>应排课</th>
    </tr>
    </thead>
    <tr>
      <td>3</td>
      <td>10小时</td>
      <td>40小时</td>
      <td>10小时</td>
      <td>60小时</td>
      <td>70小时</td>
    </tr>
</table>
<br>
<br>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>缺课课时(学期累计)</th>
        <th>代课课时(学期累计)</th>
    </tr>
    </thead>
    <tr>
      <td>30小时</td>
      <td>16小时</td>
    </tr>
</table>

<div style="margin: 20px">
  <a class="btn btn-primary"  href="" role="button">关联课程</a>
  @if ($teacher->status == true)
  <a class="btn btn-success" href="{{ route('teachers.index') }}" role="button">返回列表</a>
  @else
  <a class="btn btn-success" href="{{ route('leave_teachers.index') }}" role="button">返回列表</a>
  @endif
</div>
@stop
