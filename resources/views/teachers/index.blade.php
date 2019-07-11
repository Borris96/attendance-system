@extends('layouts.default')
@section('title','老师信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="get">
    老师英文名
    <input type="text" name="englishname" id="englishname"class="abc input-default" placeholder="" value="{{ old('englishname') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="" role="button">新增老师</a>
</form>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>老师编号</th>
        <th>英文名</th>
        <th>本月应排课</th>
        <th>本月实际排课</th>
        <th>累计缺课时(学期)</th>
        <th>累计代课时(学期)</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
       <tr>
            <td>1111</td>
            <td>English</td>
            <td>80小时</td>
            <td>70小时</td>
            <td>3.5小时</td>
            <td>5小时</td>
            <td>
                <a href="{{ route('teachers.show',1) }}" class="btn btn-info">查看详情</a>
                <a href="{{ route('teachers.edit',1) }}" class="btn btn-primary">关联课程</a>
                <form action="" method="POST" style="display: inline-block;">
                  {{ method_field('PATCH') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">移除老师</button>
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
