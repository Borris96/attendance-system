@extends('layouts.default')
@section('title','关联课程')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="{{route('teachers.update',$teacher->id)}}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
  <table class="table table-bordered table-hover definewidth m10">
      <tr>
          <td width="10%" class="tableleft">英文名</td>
          <td>
            <select name="teacher_id" id="">
              <option value="{{$teacher->id}}" selected>{{$teacher->staff->englishname}}</option>
            </select>
          </td>
      </tr>
      <tr>
        <td width="10%" class="tableleft">已上课程</td>
        <td>
          @foreach ($teacher->lessons as $l)
              {{$l->lesson_name}}&nbsp;
              {{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}-{{$l->classroom}}
              <br>
          @endforeach
        </td>
      </tr>
      <tr>
          <td width="10%" class="tableleft">选择课程</td>
          <td>
          <select data-placeholder="选择课程..." id="chosen-select" name="lesson_id[]" multiple>
            <option value=""></option>
            @foreach ($lessons as $l)
            <option value="{{$l->id}}">
              {{$l->lesson_name}}&nbsp;
              {{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}-{{$l->classroom}}&nbsp;{{$l->term->term_name}}
            </option>
            @endforeach
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
