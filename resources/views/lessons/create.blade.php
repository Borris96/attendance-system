@extends('layouts.default')
@section('title','新增课程')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('lessons.store',array('term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">课程名称*</td>
         <td>
          <input type="text" name="lesson_name" value="{{ old('lesson_name') }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">课程时间</td>
        <td>
          <input type="time" name="lesson_start_time" value="{{ old('lesson_start_time') }}"/>&nbsp;&nbsp;至&nbsp;&nbsp;
          <input type="time" name="lesson_end_time" value="{{ old('lesson_end_time') }}"/>
          <br>
          @if (stristr($term->term_name,'Summer'))
          <select name="day" id="day">
            <option value="All">Mon, Wed, Fri</option>
          </select>
          @else
          <select name="day" id="day">
            <option value="">请选择星期...</option>
            @foreach ($days as $d)
            <option value="{{ $d }}"
            @if (old('day') == $d)
            selected
            @endif
            >{{ $d }}</option>
            @endforeach
          </select>
          @endif
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
            <option value="{{$term->id}}">{{ $term->term_name }}</option>
          </select>
        </td>
    </tr>
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
