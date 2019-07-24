@extends('layouts.default')
@section('title','编辑换课')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('alters.update',array($alter->id,'current_term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
      <td class="tableleft">当前学期</td>
      <td>
        <input type="text" name="term_name" value="{{$term->term_name}}" disabled="">
      </td>
    </tr>
    <tr>
        <td class="tableleft">课程名称</td>
        <td>
              {{$alter->lesson->teacher->staff->englishname}}&nbsp;
              {{$alter->lesson->lesson_name}}&nbsp;
              {{$alter->lesson->day}}-{{ date('H:i',strtotime($alter->lesson->start_time))}}-{{ date('H:i',strtotime($alter->lesson->end_time)) }}-{{$alter->lesson->classroom}}
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">上课日期</td>
        <td>
          <input type="date" name="lesson_date" value="{{ $alter->lesson_date }}" disabled="">
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">时长</td>
        <td>
          {{ $alter->duration }}
        </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">调整日期*</td>
      <td>
        <input type="date" name="alter_date" value="{{$alter->alter_date}}">
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('alters.index', array('term_id'=>$current_term_id)) }}" role="button">返回代课管理</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>
  $(function(){
    $('#lesson_select').chosen();
  });
</script>

@stop
