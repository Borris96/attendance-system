@extends('layouts.default')
@section('title','新增请假')
@section('content')
<div class="container">
@include('shared._errors')
@include('shared._messages')
<form action="{{ route('absences.store') }}" method="post" class="definewidth m20">
  {{ csrf_field() }}
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工姓名*</td>
        <td>

        <select name="staff_id" id="name_select">
          <option value=""> -----请选择----- </option>
          @foreach($staffs as $staff)
            <option value="{{ $staff->id }} " @if(old('staff_id') == $staff->id) selected @endif> {{ $staff->staffname }} </option>
          @endforeach
        </select>

        </td>
    </tr>
    <tr>
        <td class="tableleft">请假类型*</td>
         <td>
          <select name="absence_type">
            <option value=""> -----请选择----- </option>
            <option value="事假" @if(old('absence_type') == '事假') selected @endif>事假</option>
            <option value='年假' @if(old('absence_type') == '年假') selected @endif>年假</option>
            <option value='病假' @if(old('absence_type') == '病假') selected @endif>病假</option>
            <option value='调休' @if(old('absence_type') == '调休') selected @endif>调休</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">请假时间*</td>
        <td>
          <input type="datetime-local" name="absence_start_time"
          @if (old('absence_start_time')!=null) value="{{ date('Y-m-d',strtotime(old('absence_start_time'))).'T'.date('H:i',strtotime(old('absence_start_time'))) }}"
          @endif
          /> &nbsp;至&nbsp;
          <input type="datetime-local" name="absence_end_time"
          @if (old('absence_end_time')!=null) value="{{ date('Y-m-d',strtotime(old('absence_end_time'))).'T'.date('H:i',strtotime(old('absence_end_time'))) }}"
          @endif
          />
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准*</td>
        <td>
          <select name="approve">
            <option value=""> -----请选择----- </option>
            <option value=1 @if(old('approve') == '是') selected @endif>是</option><option value=0 @if(old('approve') == '否') selected @endif>否</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder=""> {{ old('note') }} </textarea>
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
