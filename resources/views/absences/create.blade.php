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
        <td width="10%" class="tableleft">员工英文名*</td>
        @if ($staffs != null)
        <td>
        <select name="staff_id" id="name_select">
          <option value=""> -----请选择----- </option>
          @foreach($staffs as $staff)
            <option value="{{ $staff->id }} " @if(old('staff_id') == $staff->id) selected @endif> {{ $staff->englishname }} </option>
          @endforeach
        </select>
        </td>
        @else
        <td>
          <select name="staff_id" id="name_select">
              <option value="{{ $staff->id }}" selected> {{ $staff->englishname }} </option>
          </select>
        </td>
        @endif
    </tr>

    @if ($staffs == null)
    <tr>
      <td class="tableleft">应工作</td>
      @if ($attendance->should_work_time != null && $attendance->should_home_time != null)
      <td>
        {{$attendance->should_work_time}}~{{$attendance->should_home_time}}&nbsp;时长:{{ $attendance->should_duration }}
      </td>
      @else
      <td>无</td>
      @endif
    </tr>
    <tr>
      <td class="tableleft">实工作</td>
      @if ($attendance->actual_work_time != null && $attendance->actual_home_time != null)
      <td>
        {{$attendance->actual_work_time}}~{{$attendance->actual_home_time}}&nbsp;时长:{{ $attendance->actual_duration }}
      </td>
      @else
      <td>无</td>
      @endif
    </tr>
    @endif

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
          @if (count($staffs) != 0)
          <input type="datetime-local" name="absence_start_time"
          @if (old('absence_start_time')!=null) value="{{ date('Y-m-d',strtotime(old('absence_start_time'))).'T'.date('H:i',strtotime(old('absence_start_time'))) }}"
          @endif
          /> &nbsp;至&nbsp;
          <input type="datetime-local" name="absence_end_time"
          @if (old('absence_end_time')!=null) value="{{ date('Y-m-d',strtotime(old('absence_end_time'))).'T'.date('H:i',strtotime(old('absence_end_time'))) }}"
          @endif
          />
          @else
            @if ($attendance->should_duration !=null)
            <input type="datetime-local" name="absence_start_time" value="{{ date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)).'T'.$attendance->should_work_time }}"
            /> &nbsp;至&nbsp;
            <input type="datetime-local" name="absence_end_time" value="{{ date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)).'T'.$attendance->should_home_time }}"
            />
            @else
            <input type="datetime-local" name="absence_start_time" value="{{ date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)).'T00:00:00' }}"
            /> &nbsp;至&nbsp;
            <input type="datetime-local" name="absence_end_time" value="{{ date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)).'T00:00:00' }}"
            />
            @endif
          @endif
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
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;
            @if ($staffs != null)
            <a class="btn btn-success" href="{{ route('absences.index') }}" role="button">返回列表</a>
            @else
            <a class="btn btn-success" href="{{ route('attendances.show',$total_attendance->id) }}" role="button">返回个人考勤</a>
            @endif
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
