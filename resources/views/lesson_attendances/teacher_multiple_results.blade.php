@extends('layouts.default')
@section('title','考勤汇总信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<h4 style="text-align: center">{{ $term->term_name }} 老师上课考勤汇总</h4>
<hr>
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
          @if ($td[3]>0)
          <td>{{$td[3]}}</td>
          @else
          <td>0</td>
          @endif
        @endif
      </tr>
      @endforeach
      <tr>
          <td>合计</td>
          <td>{{$all_teacher_total_durations[$key][0]}}</td>
          <td>{{$all_teacher_total_durations[$key][1]}}</td>
          <td>{{$all_teacher_total_durations[$key][2]}}</td>
          @if ($all_teacher_total_durations[$key][3])
          <td>{{$all_teacher_total_durations[$key][3]}}</td>
          @else
          <td>0</td>
          @endif
      </tr>
    </tbody>
</table>

@if (count($all_teacher_extra_works[$key]) != 0)
<div style="text-align: center; margin-top: 5px;">加班记录</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>日期</th>
        <th>实际时长</th>
        <th>转换时长</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
      @foreach ($all_teacher_extra_works[$key] as $atew)
      <tr>
          <td>{{ date('Y-m-d', strtotime($atew->extra_work_start_time)) }}</td>
          <td>{{ ($atew->duration)*1.0 }}</td>
          @if ($atew->extra_work_type == '测试')
          <td>{{ ($atew->duration)*1.2 }}</td>
          <td>{{$atew->extra_work_type}},1.2倍抵课时</td>
          @else
          <td>{{ ($atew->duration)*1.0 }}</td>
          <td>{{$atew->extra_work_type}},1:1抵课时</td>
          @endif
      </tr>
      @endforeach
      <tr>
          <td style="font-weight: bold;">总计</td>
          <td></td>
          <td>{{$all_teacher_extra_work_durations[$key]}}</td>
          <td></td>
      </tr>
      <tr>
          <td style="font-weight: bold;">缺少课时</td>
          <td></td>
          @if (($all_teacher_total_durations[$key][3] - $all_teacher_extra_work_durations[$key])>0)
          <td style="color: red;">{{$all_teacher_total_durations[$key][3] - $all_teacher_extra_work_durations[$key]}}</td>
          @else
          <td style="color: red;">0</td>
          @endif
          <td></td>
      </tr>
    </tbody>
</table>
@endif
<hr>
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
