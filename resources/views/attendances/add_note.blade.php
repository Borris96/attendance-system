@extends('layouts.default')
@section('title','补打卡')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('attendances.createAddNote',$attendance->id) }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">日期</td>
         <td>
          {{ $attendance->year }}年{{ $attendance->month}}月{{ $attendance->date }}日
        </td>
    </tr>
    <tr>
        <td class="tableleft">英文名</td>
         <td>
          {{$attendance->staff->englishname}}
        </td>
    </tr>
    <tr>
        <td class="tableleft">异常备注</td>
         <td>
          <textarea name="note" id="" cols="30" rows="5" placeholder="请填写异常原因"></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button" onclick="delcfm();">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('attendances.show',array($total_attendance->id,'month'=>$month,'year'=>$year)) }}" role="button">返回个人考勤</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>
  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }
</script>

@stop
