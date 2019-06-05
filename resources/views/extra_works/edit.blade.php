@extends('layouts.default')
@section('title','编辑加班')
@section('content')
<div class="container">
@include('shared._errors')
@include('shared._messages')
<form action="{{ route('extra_works.update',$extra_work->id) }}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工姓名*</td>
        <td>
          <input type="text" value="{{ $staff->staffname }}" disabled>
        </td>
    </tr>

    <tr>
        <td class="tableleft">加班类型*</td>
         <td>
          <input type="text" value="{{ $extra_work->extra_work_type }}" disabled>
        </td>
    </tr>


    <tr>
        <td class="tableleft">加班时间*</td>
        <td>
          <input type="datetime-local" name="extra_work_start_time" value="{{ date('Y-m-d',strtotime($extra_work->extra_work_start_time)).'T'.date('H:i',strtotime($extra_work->extra_work_start_time)) }}"
          /> &nbsp;至&nbsp;
          <input type="datetime-local" name="extra_work_end_time"
          value="{{ date('Y-m-d',strtotime($extra_work->extra_work_end_time)).'T'.date('H:i',strtotime($extra_work->extra_work_end_time)) }}"
          />
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准*</td>
        <td>
          <select name="approve">
            <option value=""> -----请选择----- </option>
            <option value=1 @if($extra_work->approve == 1) selected @endif>是</option><option value=0 @if($extra_work->approve == 0) selected @endif>否</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          @if ($extra_work->note!=null)
          <textarea name="note" id="" rows="5"> {{ $extra_work->note }} </textarea>
          @else
          <textarea name="note" id="" rows="5" placeholder="请填写修改原因"></textarea>
          @endif
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('extra_works.index') }}" role="button">返回列表</a>
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
