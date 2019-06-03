@extends('layouts.default')
@section('title','新增请假')
@section('content')
<div class="container">
@include('shared._errors')
<form action="{{ route('absences.store') }}" method="post" class="definewidth m20">
  {{ csrf_field() }}
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工姓名</td>
        <td>

        <select name="staff_id" id="name_select">
          <option value=""> -----请选择----- </option>
          @foreach($staffs as $staff)
            <option value="{{ $staff->id }}"> {{ $staff->staffname }} </option>
          @endforeach
        </select>

        </td>
    </tr>
    <tr>
        <td class="tableleft">请假类型</td>
         <td>
          <select name="absence_type">
            <option value=""> -----请选择----- </option>
            <option value="事假">事假</option><option value='年假'>年假</option><option value='病假'>病假</option><option value='调休'>调休</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">请假时间</td>
        <td>
          <input type="datetime-local" name="absence_start_time" /> &nbsp;至&nbsp;
          <input type="datetime-local" name="absence_end_time"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准</td>
        <td>
          <select name="approve">
            <option value=""> -----请选择----- </option>
            <option value=1>是</option><option value=0>否</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder=""></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('absences.index') }}" role="button">返回列表</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>

  $(function(){
      $('#name_select').chosen();
  });
</script>

@stop
