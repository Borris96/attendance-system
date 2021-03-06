@extends('layouts.default')
@section('title','员工个人考勤信息')
@section('content')
@include('shared._messages')
<h4 style="margin: 20px;">员工个人考勤信息</h4>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>职位</th>
    </tr>
    </thead>
      <tr>
        <td>{{ $staff->id }}</td>
        <td><b>{{ $staff->staffname }}</b></td>
        <td><b>{{ $staff->englishname }}</b></td>
        <td>{{ $staff->department_name }}</td>
        <td>{{ $staff->position_name}}</td>
      </tr>
</table>


<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>类型</th>
        <th>日期</th>
        <th>星期</th>
        <th>应上班</th>
        <th>应下班</th>
        <th>实上班</th>
        <th>实下班</th>
        <th>迟到(分)</th>
        <th>早退(分)</th>
        <th>请假记录</th>
        <th>加班记录</th>
        <th>应工时</th>
        <th>实工时</th>
        <th>基本工时</th>
        <th>是否异常</th>
        <th>增补记录</th>
        <th>异常备注</th>
        <th>操作</th>
    </tr>
    </thead>
      @foreach ($attendances as $attendance)
       <tr>
            <td>{{ $attendance->workday_type }}</td>
            <td>{{ $attendance->year }}/{{ $attendance->month }}/{{ $attendance->date }}</td>
            <td>{{ $attendance->day }}</td>
            @if ($attendance->should_work_time != null)
            <td>{{ date("H:i", strtotime($attendance->should_work_time)) }}</td>
            @else
            <td></td>
            @endif
            @if ($attendance->should_home_time != null)
            <td>{{ date("H:i", strtotime($attendance->should_home_time)) }}</td>
            @else
            <td></td>
            @endif
            @if ($attendance->actual_work_time != null)
            <td>{{ date("H:i", strtotime($attendance->actual_work_time)) }}</td>
            @else
            <td></td>
            @endif
            @if ($attendance->actual_home_time != null)
            <td>{{ date("H:i", strtotime($attendance->actual_home_time)) }}</td>
            @else
            <td></td>
            @endif
            @if ($attendance->late_work>0)
            <td>{{ $attendance->late_work }}</td>
            @else
            <td></td>
            @endif
            @if ($attendance->early_home>0)
            <td>{{ $attendance->early_home }}</td>
            @else
            <td></td>
            @endif

            @if ($attendance->absence_id != null)
            <td style="font-size: 12px;">
              {{ $attendance->absence_type }},
              @if ($attendance->absence->approve)
              批准
              @else
              未批准
              @endif
              <br>
              {{ date("Y-m-d H:i", strtotime($attendance->absence->absence_start_time)) }}~{{ date("Y-m-d H:i", strtotime($attendance->absence->absence_end_time)) }}
              <br>
              时长:{{ $attendance->absence_duration }}
            </td>
            @else
            <td></td>
            @endif

            @if ($attendance->extraWork != null)
            <td style="font-size: 12px;">
              {{ $attendance->extraWork->extra_work_type }},
              @if ($attendance->extraWork->approve)
              批准
              @else
              未批准
              @endif
              <br>
              {{ date("H:i", strtotime($attendance->extraWork->extra_work_start_time)) }}~{{ date("H:i", strtotime($attendance->extraWork->extra_work_end_time)) }}
              <br>
              时长:{{ $attendance->extraWork->duration }}
            </td>
            @else
            <td></td>
            @endif

            <td>{{ $attendance->should_duration }}</td>
            <td>{{ $attendance->actual_duration }}</td>
            <td>{{ $attendance->basic_duration }}</td>
            @if ($attendance->abnormal == false)
            <td>否</td>
            @else
            <td style="color: red;">是</td>
            @endif
            <td style="font-size: 12px;">
              @foreach ($attendance->addTimes as $at)
              {{$at->reason}},
              <br>
              {{date('H:i', strtotime($at->add_start_time))}}~{{date('H:i', strtotime($at->add_end_time))}},
              <br>
              时长:{{$at->duration}}.
              <br>
              @endforeach
            </td>
            @if ($attendance->abnormalNote != null)
            <td>{{ $attendance->abnormalNote->note }}</td>
            @else
            <td></td>
            @endif
            <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                  异常操作 <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu">

                    <form action="{{ route('attendances.addNote', $attendance->id) }}" method="GET" style="display: inline-block;">
                      <button type="submit" class="btn btn-link" type="button" @if ($attendance->abnormalNote != null) disabled @endif>异常备注</button>
                    </form>
                    <form action="{{ route('attendances.changeAbnormal', $attendance->id) }}" method="POST" style="display: inline-block;">
                      {{ method_field('PATCH') }}
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-link" type="button" onclick="delcfm();" @if ($attendance->abnormal == false) disabled @endif>更改异常</button>
                    </form>
                    <!-- <li><a href="#">Tablet</a></li> -->
                  </ul>
                </div>


                <div class="btn-group">
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                  增补操作 <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu">

                    <form action="{{ route('attendances.clock', $attendance->id) }}" method="GET" style="display: inline-block;">

                      <button type="submit" class="btn btn-link" type="button"
                      @if ($attendance->actual_work_time != null && $attendance->actual_home_time != null)
                      disabled
                      @endif
                      @if ($attendance->abnormal == false)
                      disabled
                      @endif
                      >补打卡</button>
                    </form>

                    <form action="{{ route('attendances.addTime', $attendance->id) }}" method="GET" style="display: inline-block;">
                      <button type="submit" class="btn btn-link" type="button"
                      @if ($attendance->actual_work_time == null || $attendance->should_work_time == null || $attendance->should_home_time == null || $attendance->actual_home_time == null)
                      disabled
                      @endif
                      @if ($attendance->abnormal == false)
                      disabled
                      @endif
                      >补工时</button>
                    </form>
                    <br>
                    <!-- <li><a href="#">Tablet</a></li> -->
                    <form action="{{ route('attendances.createExtra', $attendance->id)}}" method="GET" style="display: inline-block;">
                      <button type="submit" class="btn btn-link" type="button"
                      @if ($attendance->extra_work_id != null)
                      disabled
                      @elseif ($attendance->should_duration == null)
                        @if (($attendance->actual_work_time == null && $attendance->actual_home_time != null) || ($attendance->actual_work_time != null && $attendance->actual_home_time == null))
                          disabled
                        @endif
                      @elseif ($attendance->should_duration != null)
                        @if ($attendance->actual_work_time == null || $attendance->actual_home_time == null)
                          disabled
                        @endif
                      @endif
                      >补加班</button>
                    </form>

                    <form action="{{ route('attendances.createAbsence', $attendance->id) }}" method="GET" style="display: inline-block;">
                      <button type="submit" class="btn btn-link" type="button"
                      @if ($attendance->absence_id != null)
                      disabled
                      @elseif ($attendance->should_duration == null)
                      disabled
                      @endif
                      @if ($attendance->should_duration != null)
                        @if (($attendance->actual_work_time == null && $attendance->actual_home_time != null) || ($attendance->actual_work_time != null && $attendance->actual_home_time == null))
                          disabled
                        @endif
                      @elseif ($attendance->abnormal == false)
                      disabled
                      @endif
                      >补请假</button>
                    </form>
                  </ul>
                </div>

                <form action="{{ route('attendances.basic',$attendance->id) }}" method="GET" style="display: inline-block; margin: 4px;">
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-info" type="button"
                  @if ($attendance->basic_duration == null)
                  disabled
                  @endif
                  >更改基本工时</button>
                </form>
            </td>
        </tr>
      @endforeach
</table>

<div style="margin: 20px">
  <a class="btn btn-primary" href="{{ route('attendances.results',array('year'=>$year,'month'=>$month)) }}" role="button">返回考勤汇总</a>
  <a class="btn btn-primary" href="{{ route('attendances.results') }}" role="button">返回查询界面</a>
</div>

<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }

</script>
@stop
