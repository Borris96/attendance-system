@extends('layouts.default')
@section('title','请假信息')
@section('content')

<form class="form-inline definewidth m20" action="{{ route('absences.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
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
        <th>创建日期</th>
        <th>上次修改</th>
        <th>操作</th>
    </tr>
    </thead>
    @foreach ($absences as $absence)
       <tr>
            <td>{{ $absence->staff->id }}</td>
            <td>{{ $absence->staff->staffname }}</td>
            <td>{{ $absence->staff->englishname }}</td>
            <td>{{ $absence->staff->department_name }}</td>
            <td>{{ $absence->absence_type }}</td>
            <td>{{ date("Y-m-d H:i", strtotime($absence->absence_start_time)) }} 至 {{ date("Y-m-d H:i", strtotime($absence->absence_end_time)) }}</td>
            <td>{{ $absence->duration }}</td>
            <td>
              @if ($absence->approve == true) 是
              @else 否
              @endif
            </td>
            <td>{{ $absence->note }}</td>
            <td>{{ $absence->created_at }}</td>
            <td>{{ $absence->updated_at }}</td>
            <td>
                <a href="">编辑</a> | <!-- route('staffs.edit', $staff->id) -->
                <a href="" onclick="delcfm()">删除</a> <!-- route('staffs.destroy', $staff->id) -->
            </td>
        </tr>
      @endforeach
</table>

{{ $absences->links() }}

<script>

  function delcfm() {
      if (!confirm("确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
