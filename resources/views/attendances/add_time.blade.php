@extends('layouts.default')
@section('title','增补工时')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('attendances.createAddTime',$attendance->id) }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">日期</td>
         <td>
          <input type="text" name="date" value="{{ $attendance->year }}年{{ $attendance->month}}月{{ $attendance->date }}日" disabled>
        </td>
    </tr>
    <tr>
        <td class="tableleft">英文名</td>
         <td>
          {{$attendance->staff->englishname}}
        </td>
    </tr>
    <tr>
        <td class="tableleft">增补开始时间*</td>
        <td>
          <input type="time" name="add_start_time" value="{{ old('add_start_time') }}"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">增补结束时间*</td>
        <td>
          <input type="time" name="add_end_time" value="{{ old('add_end_time') }}"/>
        </td>
    </tr>
    <tr>
      <td class="tableleft">增补原因*</td>
      <td>
        <textarea name="reason" id="" rows="5" placeholder=""> {{ old('reason') }} </textarea>
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('attendances.show',array($total_attendance->id,'month'=>$month,'year'=>$year)) }}" role="button">返回个人考勤</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>

</script>

@stop
