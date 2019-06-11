@extends('layouts.default')
@section('title','员工信息')
@section('content')
@include('shared._messages')
<form class="form-inline definewidth m20" action="{{ route('staffs.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('staffs.create') }}" role="button">新增员工</a>
    &nbsp;&nbsp;
    <a href="{{route('leave_staffs.index')}}" class="btn btn-info">查看离职员工</a>
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
        <th>操作</th>
    </tr>
    </thead>
    @foreach ($staffs as $staff)
       <tr>
            <td>{{ $staff->id }}</td>
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
            <td>{{ $staff->join_company }}</td>
            <td>{{ $staff->work_year }}</td>
            <td>{{ $staff->annual_holiday }}</td>
            <td>{{ $staff->remaining_annual_holiday }}</td>
            @if ($staff->lieu != null)
              <td>{{ $staff->lieu->total_time }}</td>
              <td>{{ $staff->lieu->remaining_time }}</td>
            @else
              <td>0.00</td>
              <td>0.00</td>
            @endif
            <td>
                <a href="{{ route('staffs.show',$staff->id) }}" class="btn btn-info">详情</a>
                <a href="{{ route('staffs.edit',$staff->id) }}" class="btn btn-primary">编辑</a>

                <form action="{{ route('staffs.leave', $staff->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('PATCH') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">办理离职</button>
                </form>
            </td>
        </tr>
      @endforeach
</table>

  {{ $staffs->links() }} <!-- show paginate -->


<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }

</script>


@stop
