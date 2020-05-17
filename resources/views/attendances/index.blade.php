@extends('layouts.default')
@section('title','考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<div class="container" style="margin: 5% auto;">
<table class="table table-bordered table-hover m10" style="width: 500px; margin:0 auto;">
    <thead>
    <tr>
        <th style="text-align: center;">上传考勤表</th>
    </tr>
    </thead>

<form class="form-inline definewidth m20" method="POST" action="{{ route('attendances.import') }}" enctype="multipart/form-data">
      <tr>
        <td style="text-align: center;">
          {{ csrf_field() }}
          选择考勤表：
          <input id="file" type="file" class="form-control" name="select_file" accept="">
          <br><br>
          <button type="submit" class="btn btn-success">上传表格</button>
          <br><br>
          <p style="font-size: 11px">"考勤月报"表请另存为成 xlsx 格式文件再上传</p>
        </td>
      </tr>
</form>
</table>
<br><br><br><br><br>
<table class="table table-bordered table-hover m10" style="width: 500px; margin:0 auto;">
    <thead>
    <tr>
        <th style="text-align: center;">查询考勤记录</th>
    </tr>
    </thead>

    <form class="form-inline definewidth m20" action="{{ route('attendances.results') }}" method="GET">
      <tr>
        <td style="text-align: center;">
        员工：
        <select name="staff_id" id="name_select">
          <option value=""> -----请选择----- </option>
          @foreach($staffs as $staff)
            <option value="{{ $staff->id }} " @if(old('staff_id') == $staff->id) selected @endif> {{ $staff->englishname }} </option>
          @endforeach
        </select>
        <br><br>
        年份：
          <select name="year" id="year">
            <option value=""> -----请选择----- </option>
            @for ($i=2010; $i<=2030; $i++)
              <option value="{{$i}}" @if (date('Y') == $i) selected @endif>{{$i}}</option>
            @endfor
          </select>
          <br>
        月份：
          <select name="month" id="month">
            <option value=""> -----请选择----- </option>
            @for ($i=1; $i<=12; $i++)
              <option value="{{ $i }}" @if (date('m')-1 == $i) selected @endif>{{$i}}月</option>
            @endfor
          </select>
          <br>
          <button type="submit" class="btn btn-primary">查询</button>
        </td>
      </tr>
    </form>
</table>
</div>

<script>

  $(function(){
      $('#name_select').chosen();
  });
</script>
@stop
