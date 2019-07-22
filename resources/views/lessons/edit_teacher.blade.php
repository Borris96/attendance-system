@extends('layouts.default')
@section('title','更换老师')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('lessons.update_teacher',array($lesson->id,'term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">课程名称*</td>
         <td>
          {{ $lesson->lesson_name }}
        </td>
    </tr>
    <tr>
        <td class="tableleft">课程时间</td>
        <td>
          {{$lesson->day}}-{{ date('H:i',strtotime($lesson->start_time))}}-{{ date('H:i',strtotime($lesson->end_time)) }}
        </td>
    </tr>
    <tr>
        <td class="tableleft">教室</td>
         <td>
          {{ $lesson->classroom }}
        </td>
    </tr>
    <tr>
        <td class="tableleft">上课学期</td>
        <td>
          {{ $lesson->term->term_name }}
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">原上课老师</td>
        <td>
          {{ $lesson->teacher->staff->englishname }}
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">现上课老师</td>
        <td>
        <select name="current_teacher_id" id="name_select">
          <option value="">请选择老师...</option>
          @foreach ($teachers as $teacher)
          <option value="{{ $teacher->id }}"
          @if (old('current_teacher_id') == $teacher->id)
          selected
          @endif
          >{{ $teacher->staff->englishname }}</option>
          @endforeach
        </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">生效日期*</td>
         <td>
          <input type="date" name="effective_date" value="{{ date('Y-m-d') }}">
        </td>
    </tr>

    @if (count($lesson->lessonUpdates) > 1)
    <tr>
      <td width="10%" class="tableleft">历史记录</td>
      <td>
        @foreach ($lesson->lessonUpdates as $key=>$lu)
          @if ($key != count($lesson->lessonUpdates)-1)
          原上课老师:{{$lu->teacher->staff->englishname}}&nbsp;有效日期:{{ $lu->start_date }}~{{ $lu->end_date }}<br>
          @endif
        @endforeach
      </td>
    </tr>
    @endif
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('lessons.index',array('term_id'=>$current_term_id)) }}" role="button">返回课程管理</a>
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
