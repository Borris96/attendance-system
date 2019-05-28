@extends('layouts.default')
@section('title','时薪信息')
@section('content')

<form class="form-inline definewidth m20" action="" method="get">
<a class="btn btn-success" href="{{ route('salarys.create') }}" role="button">新增时薪</a>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>编号</th>
        <th>类型</th>
        <th>时薪</th>
        <th>备注</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>001</td>
            <td>全职</td>
            <td>50</td>
            <td>一条备注</td>
            <td>
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
