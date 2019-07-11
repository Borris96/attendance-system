@extends('layouts.default')
@section('title','新增课程')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('lessons.store') }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">课程名称*</td>
         <td>
          <input type="text" name="lessonname" value="{{ old('lessonname') }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">课程时间*</td>
        <td>
          <input type="time" name="lesson_start_time" value="{{ old('lesson_start_time') }}"/>&nbsp;&nbsp;至&nbsp;&nbsp;
          <input type="time" name="lesson_end_time" value="{{ old('lesson_end_time') }}"/>
          <br>
          <select name="day" id="day">
            <option value="Fri">Fri</option>
            <option value="Sat">Sat</option>
            <option value="Sun">Sun</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">教室*</td>
         <td>
          <input type="text" name="classroom" value="{{ old('classroom') }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">上课学期*</td>
        <td>
          <select name="term_id" id="term_id">
            <option value="1">2019 Spring</option>
            <option value="2">2019 Summer</option>
            <option value="3">2019 Fall</option>
            <option value="4">2020 Spring</option>
          </select>
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">上课老师</td>
        <td>
        <select name="teacher_id" id="name_select">
          <option value=""> -----请选择----- </option>
          <option value="1">Jack</option>
          <option value="2">Mark</option>
          <option value="3">James</option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('lessons.index') }}" role="button">返回课程管理</a>
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
