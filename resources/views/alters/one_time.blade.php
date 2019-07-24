@extends('layouts.default')
@section('title','一键换课')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('alters.store_all',array('current_term_id'=>$current_term_id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ csrf_field() }}
    <tr>
      <td class="tableleft">当前学期</td>
      <td>
        <input type="text" name="term_name" value="{{$term->term_name}}" disabled="">
      </td>
    </tr>

    <tr>
        <td width="10%" class="tableleft">上课日期*</td>
        <td>
          <input type="date" name="lesson_date" value="{{ old('lesson_date') }}">
        </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">调整日期*</td>
      <td>
        <input type="date" name="alter_date" value="{{old('alter_date')}}">
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
