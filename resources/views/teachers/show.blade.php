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

    @if (count($lessons) != 0)
    @foreach ($lessons as $l)
      <tr>
        @if ($flag)
          @if ($l->day == 'Mon')
          <td>{{ $l->lesson_name }}</td>
          <td>{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}</td>
          <td>{{$l->classroom}}</td>
          <td>{{$l->duration}}</td>
          @endif
        @else
          <td>{{ $l->lesson_name }}</td>
          <td>{{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}</td>
          <td>{{$l->classroom}}</td>
          <td>{{$l->duration}}</td>
        @endif
      </tr>
    @endforeach
</table>
@else
</table>
@include('shared._nothing')
@endif

<h5 style="margin: 20px;">每月时长</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>月份</th>
        @if (stristr($term->term_name,'Summer'))
        <th>周一</th>
        <th>周三</th>
        <th>周五</th>
        <th>其他</th>
        @else
        <th>周五</th>
        <th>周六</th>
        <th>周日</th>
        <th>其他</th>
        @endif
        <th>实际排课</th>
        <th>应排课</th>
    </tr>
    </thead>
    @if (count($month_durations) != 0)
    @if (stristr($term->term_name,'Summer'))
    @foreach ($month_durations as $md)
      @if (array_key_exists($md->month,$month_should_durations))
      <tr>
        <td>{{$md->month}}</td>
        <td>{{$md->mon_duration}}</td>
        <td>{{$md->wed_duration}}</td>
        <td>{{$md->fri_duration}}</td>
        <td>{{$md->other_duration}}</td>
        <td>{{$md->actual_duration}}</td>
        <td>{{$month_should_durations[$md->month]}}</td>
      </tr>
      @endif
    @endforeach
    @else
    @foreach ($month_durations as $md)
      @if (array_key_exists($md->month,$month_should_durations))
      <tr>
        <td>{{$md->month}}</td>
        <td>{{$md->fri_duration}}</td>
        <td>{{$md->sat_duration}}</td>
        <td>{{$md->sun_duration}}</td>
        <td>{{$md->other_duration}}</td>
        <td>{{$md->actual_duration}}</td>
        <td>{{$month_should_durations[$md->month]}}</td>
      </tr>
      @endif
    @endforeach
    @endif
</table>
@else
</table>
@include('shared._nothing')
@endif

<br>
<br>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>缺课课时(学期累计)</th>
        <th>代课课时(学期累计)</th>
    </tr>
    </thead>
    @if (count($term_totals) != 0)
    <tr>
      @foreach ($term_totals as $tt)
      @if ($tt->total_missing_hours != null)
      <td>{{ $tt->total_missing_hours }}小时</td>
      @else
      <td>0小时</td>
      @endif
      @if  ($tt->total_substitute_hours != null)
      <td>{{ $tt->total_substitute_hours}}小时</td>
      @else
      <td>0小时</td>
      @endif
      @endforeach
    </tr>
</table>
@else
</table>
@include('shared._nothing')
@endif

<h5 style="margin: 20px;">课程时间变更信息</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>课程名称</th>
        <th>星期</th>
        <th>时长</th>
        <th>有效日期</th>
    </tr>
    </thead>
    @if (count($lessons) != 0)
      @foreach ($lessons as $lesson)
      @foreach ($lesson->lessonUpdates as $key=>$lu)
        @if($key < count($lesson->lessonUpdates)-1)
        <tr>
          <td>
            {{ $lesson->lesson_name }}
          </td>
          <td>
            {{$lu->day}}
          </td>
          <td>
            {{ $lu->duration }}
          </td>
          <td>
            {{ $lu->start_date }} ~ {{ $lu->end_date }}
          </td>
        </tr>
        @endif
      @endforeach
      @endforeach
</table>
@else
</table>
@include('shared._nothing')
@endif

<div style="margin: 20px">
  @if ($teacher->status == true)
  <a class="btn btn-primary"  href="{{ route('teachers.edit',array($teacher->id,'term_id'=>$current_term_id)) }}" role="button">关联课程</a>&nbsp;&nbsp;
  @endif
  <a class="btn btn-success" href="{{ route('teachers.index',array('term_id'=>$current_term_id)) }}" role="button">返回列表</a>
</div>
@stop
