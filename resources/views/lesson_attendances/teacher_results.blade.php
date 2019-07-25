@extends('layouts.default')
@section('title','考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<h4 style="text-align: center">{{ $term->term_name }}-{{ $month }}月 上课考勤汇总</h4>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>老师编号</th>
        <th>英文名</th>
        <th>老师类型</th>
        <th>应排课</th>
        <th>实际排课</th>
        <th>实际上课</th>
        <th>缺少课时</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
      @foreach ($teachers as $key=>$t)
        <tr>
            <td>{{ $t->staff->id }}</td>
            <td>{{ $t->staff->englishname }}</td>
            <td>{{ $t->staff->position_name }}</td>
            <td>{{ $should_durations[$key] }}</td>
            <td>{{ $actual_durations[$key] }}</td>
            <td>{{ $actual_goes[$key] }}</td>
            @if ($should_durations[$key] - $actual_goes[$key] > 0)
            <td>{{ $should_durations[$key] - $actual_goes[$key] }}</td>
            @else
            <td>0</td>
            @endif
        </tr>
      @endforeach
    </tbody>
</table>

@if (count($teachers)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif
<div style="margin: 20px">
  <a class="btn btn-success" href="{{ route('lesson_attendances.index',array('term_id'=>$term->term_id)) }}" role="button">返回</a>
  &nbsp;&nbsp;
  <div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    导出上课考勤汇总 <span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
    <form action="{{ route('lesson_attendances.export_one', array('term_id'=>$term_id,'start_month'=>$month, 'option'=>'全职教师')) }}" method="POST">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">全职老师</button>
    </form>

    <form action="{{ route('lesson_attendances.export_one', array('term_id'=>$term_id,'start_month'=>$month, 'option'=>'兼职教师')) }}" method="POST">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-link" type="button">兼职老师</button>
    </form>
    </ul>
  </div>
</div>



@stop
