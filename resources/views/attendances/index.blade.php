@extends('layouts.default')
@section('title','考勤信息')
@section('content')
@include('shared._messages')
@include('shared._errors')
<form class="form-inline definewidth m20" action="{{ route('attendances.results') }}" method="GET">
    员工英文名
    <input type="text" name="englishname" id="enlishname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <select name="year" id="year">
      @for ($i=2019; $i<=2030; $i++)
        <option value="{{$i}}">{{$i}}</option>
      @endfor
    </select>
    &nbsp;&nbsp;
    <select name="month" id="month">
        <option value="1">一月</option>
        <option value="2">二月</option>
        <option value="3">三月</option>
        <option value="4">四月</option>
        <option value="5">五月</option>
        <option value="6">六月</option>
        <option value="7">七月</option>
        <option value="8">八月</option>
        <option value="9">九月</option>
        <option value="10">十月</option>
        <option value="11">十一月</option>
        <option value="12">十二月</option>
    </select>
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
</form>

<form class="form-inline definewidth m20" method="POST" action="{{ route('attendances.import') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    选择表格
    <input id="file" type="file" class="form-control" name="select_file" accept="">
    <button type="submit" class="btn btn-success">上传表格</button>
</form>


<table class="table table-bordered table-hover m10" style="width: 500px; margin:0 auto;">
    <thead>
    <tr>
        <th style="text-align: center;">查询考勤记录</th>
    </tr>
    </thead>

    <form class="form-inline definewidth m20" action="{{ route('attendances.results') }}" method="GET">
      <tr>
        <td style="text-align: center;">
        员工英文名：<input type="text" name="englishname" id="enlishname"class="abc input-default" placeholder="" value="">
        <br>
        年份：
          <select name="year" id="year">
            @for ($i=2019; $i<=2030; $i++)
              <option value="{{$i}}">{{$i}}</option>
            @endfor
          </select>
          <br>
        月份：
          <select name="month" id="month">
              <option value="1">一月</option>
              <option value="2">二月</option>
              <option value="3">三月</option>
              <option value="4">四月</option>
              <option value="5">五月</option>
              <option value="6">六月</option>
              <option value="7">七月</option>
              <option value="8">八月</option>
              <option value="9">九月</option>
              <option value="10">十月</option>
              <option value="11">十一月</option>
              <option value="12">十二月</option>
          </select>
          <br>
          <button type="submit" class="btn btn-primary">查询</button>
        </td>
      </tr>
    </form>
</table>
<br>
<br>
<br>
<br>
<br>
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
          选择表格
          <input id="file" type="file" class="form-control" name="select_file" accept="">
          <button type="submit" class="btn btn-success">上传表格</button>
        </td>
      </tr>
    </form>
</table>
@stop
