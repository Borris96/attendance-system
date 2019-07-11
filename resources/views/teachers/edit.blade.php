@extends('layouts.default')
@section('title','关联课程')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="          " method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
  <table class="table table-bordered table-hover definewidth m10">
      <tr>
          <td width="10%" class="tableleft">英文名</td>
          <td>Jack</td>
      </tr>
      <tr>
          <td width="10%" class="tableleft">选择课程</td>
          <td>
          <select data-placeholder="选择课程..." id="chosen-select" name="lesson_id" multiple>
            <option value=""></option>
            <option value="1">G1 Sun 16:00-18:00-13</option>
            <option value="2">K2 Sat 10:00-14:00-11</option>
            <option value="3">SAT Fri 11:00-15:00-12</option>
          </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft"></td>
          <td>
              <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('teachers.index') }}" role="button">返回列表</a>
          </td>
      </tr>
  </table>
</form>

</div>
<script>
  $(function(){
      $('#chosen-select').chosen({no_results_text: "Oops, nothing found!"});
  });
</script>

@stop
