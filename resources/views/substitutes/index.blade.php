@extends('layouts.default')
@section('title','代课信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="GET">
    课程名称
    <input type="text" name="lessonname" id="lessonname"class="abc input-default" placeholder="" value="{{ old('lessonname') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('substitutes.create') }}" role="button">新增代课</a>
</form>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>课程名称</th>
        <th>上课时间</th>
        <th>教室</th>
        <th>学期</th>
        <th>时长</th>
        <th>原老师</th>
        <th>代课老师</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
       <tr>
            <td>G1</td>
            <td>5/26 Sat 10:00-12:00</td>
            <td>5</td>
            <td>2019 Fall</td>
            <td>2小时</td>
            <td>Jack</td>
            <td>James</td>
            <td>
                <a href="{{ route('substitutes.edit',1) }}" class="btn btn-primary">编辑</a>
                <form action="{{ route('substitutes.destroy',1) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">删除</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>

if (count($staffs)>config('page.PAGE_SIZE'))
include('shared._pagination')
endif

<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }

</script>

@stop
