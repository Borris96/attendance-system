@extends('layouts.default')
@section('title','补打卡')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('attendances.updateClock',$attendance->id) }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">日期</td>
         <td>
          <input type="text" name="date" value="{{ $attendance->year }}年{{ $attendance->month}}月{{ $attendance->date }}日" disabled>
        </td>
    </tr>
    <tr>
        <td class="tableleft">上班时间</td>
        <td>
          <input type="time" name="actual_work_time" value="{{ $attendance->actual_work_time }}"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">下班时间</td>
        <td>
          <input type="time" name="actual_home_time" value="{{ $attendance->actual_home_time }}"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('attendances.show',$total_attendance->id) }}" role="button">返回个人考勤</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>

</script>

@stop
