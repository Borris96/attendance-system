@extends('layouts.default')
@section('title','修改当前学期')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('update_term',array('term_id'=>$term->id)) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
      <td class="tableleft">当前学期*</td>
      <td>
        <input type="text" name="term_name" value="{{ $term->term_name }}">
        <br>
        <span style="color: gray;">如: 2019 Fall, 2020 Summer 1 ...</span>
      </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">学期开始日期*</td>
        <td>
          <input type="date" name="start_date" value="{{ $term->start_date }}">
        </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">学期结束日期*</td>
      <td>
        <input type="date" name="end_date" value="{{ $term->end_date }}">
      </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('teachers.index', array('term_id'=>$term->id)) }}" role="button">返回老师信息管理</a>
        </td>
    </tr>
</table>
</form>
</div>

<script>
</script>

@stop
