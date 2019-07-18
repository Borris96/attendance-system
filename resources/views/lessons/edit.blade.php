@extends('layouts.default')
@section('title','编辑课程')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('lessons.update',array($lesson->id,'term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">课程名称*</td>
         <td>
          <input type="text" name="lesson_name" value="{{ $lesson->lesson_name }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">课程时间</td>
        <td>
          <input type="time" name="lesson_start_time" value="{{ $lesson->start_time }}"/>&nbsp;&nbsp;至&nbsp;&nbsp;
          <input type="time" name="lesson_end_time" value="{{ $lesson->end_time }}"/>
          <br>

          <select name="day" id="day">
            <option value="">请选择星期...</option>
            @foreach ($days as $d)
            <option value="{{ $d }}"
            @if ($lesson->day == $d)
            selected
            @endif
            >{{ $d }}</option>
            @endforeach
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">教室*</td>
         <td>
          <input type="text" name="classroom" value="{{ $lesson->classroom }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">上课学期*</td>
        <td>
          {{ $lesson->term->term_name }}
        </td>
    </tr>
    @if ($lesson->teacher_id == null)
    <tr>
        <td width="10%" class="tableleft">上课老师</td>
        <td>
        <select name="teacher_id" id="name_select">
          <option value="">请选择老师...</option>
          @foreach ($teachers as $teacher)
          <option value="{{ $teacher->id }}"
          @if (old('teacher_id') == $teacher->id)
          selected
          @endif
          >{{ $teacher->staff->englishname }}</option>
          @endforeach
        </select>
        </td>
    </tr>
    @else
    <tr>
      <td width="10%" class="tableleft">上课老师</td>
      <td>
        <select name="teacher_id">
          <option value="{{ $lesson->teacher->id }}">{{ $lesson->teacher->staff->englishname }}</option>
        </select>
      </td>
    </tr>
    @endif
    <tr>
        <td class="tableleft">生效日期*</td>
         <td>
          <input type="date" name="effective_date" value="{{ date('Y-m-d') }}">
        </td>
    </tr>
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
