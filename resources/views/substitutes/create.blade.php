@extends('layouts.default')
@section('title','新增代课')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('substitutes.store',array('current_term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
      <td class="tableleft">当前学期</td>
      <td>
        <input type="text" name="term_name" value="{{$term->term_name}}" disabled="">
      </td>
    </tr>
    <tr>
        <td class="tableleft">课程名称*</td>
        <td>
          <select name="lesson_id" id="lesson_select">
            <option value=""> 选择课程... </option>
            @foreach ($lessons as $l)
            <option value="{{$l->id}}"
              @if (old('lesson_id') == $l->id)
                selected
              @endif
            >
              {{$l->teacher->staff->englishname}}&nbsp;
              {{$l->lesson_name}}&nbsp;
              {{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}-{{$l->classroom}}
            </option>
            @endforeach
          </select>
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">代课老师</td>
        <td>
        <select name="substitute_teacher_id" id="substitute_name_select">
          <option value=""> 选择代课老师... </option>
          @foreach ($teachers as $t)
          <option value="{{ $t->id }}"
            @if (old('substitute_teacher_id') == $t->id)
              selected
            @endif
          >{{ $t->staff->englishname }}</option>
          @endforeach
        </select>
        </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">上课日期*</td>
      <td>
        <input type="date" name="lesson_date" value="{{old('lesson_date')}}">
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('substitutes.index', array('term_id'=>$current_term_id)) }}" role="button">返回代课管理</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>
  $(function(){
    $('#lesson_select').chosen();
    $('#origin_name_select').chosen();
    $('#substitute_name_select').chosen();
  });
</script>

@stop
