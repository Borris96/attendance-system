@extends('layouts.default')
@section('title','新增调休')
@section('content')
<form action="{{ route('holidays.index') }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td class="tableleft">调休类型</td>
         <td>
          <select name="holiday_types">
<!--             <option disabled="disabled">请选择</option> -->
            <option value='xiuxi'>休息</option>
            <option value="gongzuo">工作</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">调休日期</td>
        <td>
          <input type="date" name="holiday_date" id="date-picker"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder="请备注节假日名称"></textarea>
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

<script>
  var defaultDate = document.querySelectorAll('#date-picker');
  for (var i = 0; i<defaultDate.length; i++) {
    defaultDate[i].valueAsDate = new Date();
  }
</script>

@stop
