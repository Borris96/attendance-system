@extends('layouts.default')
@section('title','编辑请假')
@section('content')
<div class="container">
@include('shared._errors')
@include('shared._messages')
<form action="{{ route('absences.update', $absence->id) }}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工英文名</td>
          <td>
            <input type="text" value="{{ $staff->englishname }}" disabled />
          </td>
        </td>
    </tr>
    <tr>
        <td class="tableleft">请假类型</td>
         <td>
            <input type="text" value="{{ $absence->absence_type }}" disabled />
        </td>
    </tr>
    <tr>
        <td class="tableleft">请假时间*</td>
        <td>
          <input type="datetime-local" name="absence_start_time" value="{{ date('Y-m-d',strtotime($absence->absence_start_time)).'T'.date('H:i',strtotime($absence->absence_start_time)) }}"
          /> &nbsp;至&nbsp;
          <input type="datetime-local" name="absence_end_time"
          value="{{ date('Y-m-d',strtotime($absence->absence_end_time)).'T'.date('H:i',strtotime($absence->absence_end_time)) }}"
          />
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准*</td>
        <td>
          <select name="approve">
            <option value=""> -----请选择----- </option>
            <option value=1 @if($absence->approve == 1) selected @endif>是</option><option value=0 @if($absence->approve == 0) selected @endif>否</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注*</td>
        <td>
          @if ($absence->note!=null)
          <textarea name="note" id="" rows="5"> {{ $absence->note }} </textarea>
          @else
          <textarea name="note" id="" rows="5" placeholder="请填写修改原因"></textarea>
          @endif
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
