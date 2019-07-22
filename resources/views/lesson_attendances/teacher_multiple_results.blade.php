@extends('layouts.default')
@section('title','考勤汇总信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<h4 style="text-align: center">{{ $term->term_name }} 老师上课考勤汇总</h4>
@foreach ($teachers as $key=>$t)
<h5 style="text-align: center">{{ $t->staff->englishname }}-{{ $t->staff->position_name }}</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>月份</th>
        <th>应排课</th>
        <th>实际排课</th>
        <th>实际上课</th>
        <th>缺少课时</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
      @foreach ($all_teacher_durations[$key] as $m=>$td) <!-- $td是这个老师每一个月的duration -->
      <tr>
        @if ($td != null)
          <td>{{$m}}</td>
          <td>{{$td[0]}}</td>
          <td>{{$td[1]}}</td>
          <td>{{$td[2]}}</td>
          <td>{{$td[3]}}</td>
        @endif
      </tr>
      @endforeach
      <tr>
          <td>合计</td>
          <td>{{$all_teacher_total_durations[$key][0]}}</td>
          <td>{{$all_teacher_total_durations[$key][1]}}</td>
          <td>{{$all_teacher_total_durations[$key][2]}}</td>
          <td>{{$all_teacher_total_durations[$key][3]}}</td>
      </tr>
    </tbody>
</table>
@endforeach

@if(count($teachers)>config('page.PAGE_SIZE'))
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
