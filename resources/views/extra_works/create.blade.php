@extends('layouts.default')
@section('title','新增加班')
@section('content')
<div class="container">
@include('shared._errors')
@include('shared._messages')
<form action="{{ route('extra_works.store') }}" method="post" class="definewidth m20">
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
        <td class="tableleft">加班类型*</td>
         <td>
          <select name="extra_work_type">
            <option value=""> -----请选择----- </option>
            <option value='带薪'' @if(old('extra_work_type') == '带薪') selected @endif>带薪</option>
            <option value='调休' @if(old('extra_work_type') == '调休') selected @endif>调休</option>
          </select>
        </td>
    </tr>


    <tr>
        <td class="tableleft">加班时间*</td>
        <td>
          <input type="datetime-local" name="extra_work_start_time"
          @if (old('extra_work_start_time')!=null) value="{{ date('Y-m-d',strtotime(old('extra_work_start_time'))).'T'.date('H:i',strtotime(old('extra_work_start_time'))) }}"
          @endif
          /> &nbsp;至&nbsp;
          <input type="datetime-local" name="extra_work_end_time"
          @if (old('extra_work_end_time')!=null) value="{{ date('Y-m-d',strtotime(old('extra_work_end_time'))).'T'.date('H:i',strtotime(old('extra_work_end_time'))) }}"
          @endif
          />
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准*</td>
        <td>
          <select name="approve">
            <option value=""> -----请选择----- </option>
            <option value=1 @if(old('approve') == 1) selected @endif>是</option><option value=0 @if(old('approve') == 0) selected @endif>否</option>
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
