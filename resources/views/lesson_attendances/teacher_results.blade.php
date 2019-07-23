@extends('layouts.default')
@section('title','考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<h4 style="text-align: center">{{ $term->term_name }} {{ $month }}月 上课考勤汇总</h4>
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
            <td>{{ $should_durations[$key] - $actual_goes[$key] }}</td>
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
  <form action="" method="POST" style="display: inline-block;">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-warning" type="button">导出上课考勤汇总</button>
  </form>
</div>



@stop
