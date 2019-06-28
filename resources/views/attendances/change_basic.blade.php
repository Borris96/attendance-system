@extends('layouts.default')
@section('title','修改基本工时')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('attendances.changeBasic',$attendance->id) }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
      <td class="tableleft">英文名</td>
      <td>
        {{ $attendance->staff->englishname }}
      </td>
    </tr>
    <tr>
        <td class="tableleft">日期</td>
         <td>
          {{ $attendance->year }}年{{ $attendance->month}}月{{ $attendance->date }}日
        </td>
    </tr>
    <tr>
      <td class="tableleft">应工作</td>
      <td>
        {{$attendance->should_work_time}}~{{$attendance->should_home_time}}&nbsp;时长:{{ $attendance->should_duration }}
      </td>
    </tr>
    <tr>
      <td class="tableleft">实工作</td>
      <td>
        {{$attendance->actual_work_time}}~{{$attendance->actual_home_time}}&nbsp;时长:{{ $attendance->actual_duration }}
      </td>
    </tr>
    <tr>
      <td class="tableleft">基本工作时长*</td>
      <td>
        <input type="text" name="basic_duration" value="{{ $attendance->basic_duration }}">
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
