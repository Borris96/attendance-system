@extends('layouts.default')
@section('title','员工信息')
@section('content')
<form class="form-inline definewidth m20" action="{{ route('staffs.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <a class="btn btn-success" href="{{ route('staffs.create') }}" role="button">新增员工</a>

</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>当前职位</th>
        <th>入职日期</th>
        <th>参加工作</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>001</td>
            <td>张三</td>
            <td>Jack</td>
            <td>教材部</td>
            <td>文员</td>
            <td>2017/07/01</td>
            <td>2015/06/01</td>
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
