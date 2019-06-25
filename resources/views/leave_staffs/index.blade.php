@extends('layouts.default')
@section('title','离职员工信息')
@section('content')
@include('shared._messages')
<form class="form-inline definewidth m20" action="{{ route('leave_staffs.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a href="{{route('staffs.index')}}" class="btn btn-info">查看在职员工</a>
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
        <th>参加工作年数</th>
        <th>年假小时数</th>
        <th>剩余小时</th>
        <th>调休小时数</th>
        <th>剩余小时</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
    @foreach ($leave_staffs as $leave_staff)
       <tr>
            <td>{{ $leave_staff->id }}</td>
            <td>{{ $leave_staff->staffname }}</td>
            <td>{{ $leave_staff->englishname }}</td>
            <td>{{ $leave_staff->department_name }}</td>
            <td>{{ $leave_staff->position_name }}</td>
            <td>{{ $leave_staff->join_company }}</td>
            <td>{{ $leave_staff->work_year }}</td>
            <td>{{ $leave_staff->annual_holiday }}</td>
            <td>{{ $leave_staff->remaining_annual_holiday }}</td>
            @if ($leave_staff->lieu != null)
              <td>{{ $leave_staff->lieu->total_time }}</td>
              <td>{{ $leave_staff->lieu->remaining_time }}</td>
            @else
              <td>0.00</td>
              <td>0.00</td>
            @endif
            <td><button class="btn btn-danger disabled" type="button">已离职</button></td>
            <td>
                <a href="{{ route('leave_staffs.show',$leave_staff->id) }}" class="btn btn-info">详情</a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

@include('shared._pagination')
<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }

</script>


@stop
