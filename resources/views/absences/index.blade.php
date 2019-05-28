@extends('layouts.default')
@section('title','请假信息')
@section('content')

<form class="form-inline definewidth m20" action="{{ route('absences.index') }}" method="get">
    员工姓名：
    <input type="text" name="username" id="username"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <a class="btn btn-success" href="{{ route('absences.create') }}" role="button">新增请假</a>

</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>请假类型</th>
        <th>请假时间</th>
        <th>时长</th>
        <th>是否批准</th>
        <th>备注</th>
        <th>上次修改</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>001</td>
            <td>张三</td>
            <td>Jack</td>
            <td>教材部</td>
            <td>事假</td>
            <td>2017/07/02 9:00 至 2017/07/02 16:00</td>
            <td>7小时</td>
            <td>否</td>
            <td>实际请假7小时</td>
            <td>2017/07/03 9:30</td>
            <td>
                <a href="">详情</a> | <!-- route('staffs.show', $staff->id) -->
                <a href="">编辑</a> | <!-- route('staffs.edit', $staff->id) -->
                <a href="" onclick="delcfm()">删除</a> <!-- route('staffs.destroy', $staff->id) -->
            </td>
        </tr>
</table>

<script>

  function delcfm() {
      if (!confirm("确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
