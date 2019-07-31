@extends('layouts.default')
@section('title','员工个人信息')
@section('content')
<h4 style="margin: 20px;">员工历史排班 - {{ $staff->staffname }}</h4>
@foreach ($staffworkdays as $key=>$sw)
<table class="table table-bordered table-hover definewidth m10">
  <h4 style="margin-left: 20px; margin-right: 20px; margin-top: 20px; margin-bottom: 0px;">

   @if ($key == 0) 入职 @else {{ $update_historys_s[$key] }} @endif
   ~
   @if ($key == count($staffworkdays)-1) 至今 @else {{ $update_historys_e[$key] }} @endif
  </h4>
    <thead>
    <tr>
        <th>星期</th>
        <th>应上班时间</th>
        <th>应下班时间</th>
    </tr>
    </thead>
    @foreach ($sw as $workday)
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
<p style="margin: 0px 20px; text-align: right;">应工作时长：{{ $total_duration[$key] }}小时</p>
@endforeach

<div style="margin: 20px">
  @if ($id != null)
  <a href="{{ route('staffs.show_part_time',array('id'=>$id)) }}" class="btn btn-success">返回详情</a>
  @else
  <a href="{{ route('staffs.show',$staff->id) }}" class="btn btn-success">返回详情</a>
  @endif
</div>


@stop
