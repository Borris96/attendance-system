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
        <th>入职日期</th>
        <th>参加工作年数</th>
    </tr>
    </thead>
       <tr>
            <td>{{ $staff->id }}</td>
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
            <td>{{ $staff->join_company }}</td>
            <td>{{ $staff->work_year }}</td>
</table>


<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>总年假(小时)</th>
        <th>剩余小时</th>
        <th>总调休(小时)</th>
        <th>剩余小时</th>
    </tr>
    </thead>
    <tr>
      @if ($staff->annual_holiday != null)
        <td>{{ round($staff->annual_holiday)}}</td>
        <td>{{ round($staff->remaining_annual_holiday)}}</td>
      @else
        <td>0</td>
        <td>0</td>
      @endif
      @if ($staff->lieu != null)
        <td>{{ round($staff->lieu->total_time) }}</td>
        <td>{{ round($staff->lieu->remaining_time) }}</td>
      @else
        <td>0</td>
        <td>0</td>
      @endif
    </tr>
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
<p style="margin: 0px 20px; text-align: right;">
  <u><a href="{{ route('staffs.show_work_time',$staff->id) }}">查看历史排班</a></u>
</p>

@if (count($work_historys) != 0 )
<table class="table table-bordered table-hover definewidth m10">
  <h4 style="margin: 0px 20px;">工作经历</h4>
    <thead>
    <tr>
        <th>入职日期</th>
        <th>离职日期</th>
    </tr>
    </thead>
    @foreach($work_historys as $wh)
    <tr>
        <td>{{ $wh->work_experience }}</td>
        <td>{{ $wh->leave_experience }}</td>
    </tr>
    @endforeach
</table>
@endif

<div style="margin: 20px">
  <a class="btn btn-primary"  href="{{ route('staffs.edit',$staff->id) }}" role="button">编辑信息</a>
  <a href="{{ route('staffs.edit_work_time',$staff->id) }}" class="btn btn-primary">修改排班</a>
  <a class="btn btn-success" href="{{ route('staffs.index') }}" role="button">返回列表</a>
</div>


@stop
