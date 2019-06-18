@extends('layouts.default')
@section('title','新增节假日调休')
@section('content')
<div class="container">
@include('shared._errors')
@include('shared._messages')
<form action="{{ route('holidays.update',$holiday->id) }}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td class="tableleft">节假日调休类型*</td>
         <td>
          <select name="holiday_type">
            <option value=""> -----请选择----- </option>
            <option value='休息' @if($holiday->holiday_type == '休息') selected @endif>休息</option>
            <option value='上班' @if($holiday->holiday_type == '上班') selected @endif>上班</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">调休日期*</td>
        <td> <!-- 是否需要加范围限制？ -->
          <input type="date" name="date"
            value="{{$holiday->date}}"
          />
        </td>
    </tr>
    <tr>
        <td class="tableleft">调上周几的班<br>(调休类型为上班时必填)</td>
          <td>
            <select name="workday">
              <option value=""> -----请选择----- </option>
              @foreach($workdays as $key => $workday)
              <option value='{{ $key }}' @if($workday == $holiday->workday_name) selected @endif>周{{ $workday }}</option>
              @endforeach
            </select>
          </td>
    </tr>
    <tr>
        <td class="tableleft">备注*</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder="请备注节假日名称">{{ $holiday->note }}</textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('holidays.index') }}" role="button">返回列表</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>
  var defaultDate = document.querySelectorAll('#date-picker');
  for (var i = 0; i<defaultDate.length; i++) {
    defaultDate[i].valueAsDate = new Date();
  }
</script>

@stop
