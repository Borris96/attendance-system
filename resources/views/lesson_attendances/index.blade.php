@extends('layouts.default')
@section('title','上课考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<div class="container" style="margin: 5% auto;">

<br><br><br><br><br>
<table class="table table-bordered table-hover m10" style="width: 500px; margin:0 auto;">
    <thead>
    <tr>
        <th style="text-align: center;" colspan="2">查询上课考勤记录</th>
    </tr>
    </thead>

    <form class="form-inline definewidth m20" action="{{ route('lesson_attendances.teacher_results') }}" method="GET">
      <tr>
        <td style="text-align: center;" colspan="2">
          选择学期：
          <select name="term_id" id="term_id">
            @foreach ($terms as $term)
            <option value="{{$term->id}}"
            @if ($term_id == $term->id)
            selected
            @endif
            >{{ $term->term_name }}</option>
            @endforeach
          </select>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
        开始月份：
          <select name="start_month" id="start_month">
            <option value="">请选择...</option>
            @for ($i=1; $i<=12; $i++)
              <option value="{{ $i }}" @if (date('m')-1 == $i) selected @endif>{{$i}}月</option>
            @endfor
          </select>
        </td>

        <td style="text-align: center;">
        结束月份：
          <select name="end_month" id="end_month">
            <option value="">请选择...</option>
            @for ($i=1; $i<=12; $i++)
              <option value="{{ $i }}">{{$i}}月</option>
            @endfor
          </select>
        </td>
      </tr>
      <tr>
          <td style="text-align: center;" colspan="2">
            <button type="submit" class="btn btn-primary">查询</button>
          </td>
      </tr>
    </form>
</table>
</div>

<script>

</script>
@stop
