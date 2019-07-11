@extends('layouts.default')
@section('title','编辑代课')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('substitutes.update',1) }}" method="POST" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH')}}
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">课程名称*</td>
        <td>
          <select name="lesson_id" id="lesson_select">
            <option value=""> 选择课程... </option>
            <option value="1">G1 Sat 18:00-20:00-4 2019 Fall</option>
            <option value="2">K2-2 Sun 13:00-15:00-8 2019 Fall</option>
            <option value="3">SAT Fri 10:00-11:30-13 2019 Fall</option>
          </select>
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">原上课老师*</td>
        <td>
        <select name="teacher_id" id="origin_name_select">
          <option value=""> 选择原上课老师... </option>
          <option value="1">Jack</option>
          <option value="2">Mark</option>
          <option value="3">James</option>
        </select>
        </td>
    </tr>
    <tr>
        <td width="10%" class="tableleft">代课老师</td>
        <td>
        <select name="teacher_id" id="substitute_name_select">
          <option value=""> 选择代课老师... </option>
          <option value="1">Jack</option>
          <option value="2">Mark</option>
          <option value="3">James</option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('substitutes.index') }}" role="button">返回代课管理</a>
        </td>
    </tr>
  }
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
edit.blade.php
