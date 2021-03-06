@extends('layouts.default')
@section('title','员工信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="{{ route('staffs.index') }}" method="get">
    员工英文名
    <input type="text" name="englishname" id="englishname"class="abc input-default" placeholder="" value="{{ old('englishname') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
  <div class="btn-group">
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
    新增员工 <span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
    <a class="btn btn-link" href="{{ route('staffs.create') }}" role="button">正式员工</a>
    &nbsp;&nbsp;
    <a class="btn btn-link" href="{{ route('staffs.create_part_time') }}" role="button">兼职员工</a>
    </ul>
  </div>
  &nbsp;&nbsp;

  <div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
    查看员工 <span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
    <a class="btn btn-link" href="{{ route('staffs.part_time_index') }}">兼职员工</a>
    &nbsp;&nbsp;
    <a href="{{route('leave_staffs.index')}}" class="btn btn-link">离职员工</a>
    </ul>
  </div>



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
    <tbody id="pageInfo">
    @foreach ($staffs as $staff)
       <tr>
            <td>{{ $staff->id }}</td>
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
            <td>{{ $staff->join_company }}</td>
            <td>{{ $staff->work_year }}</td>
            <td>{{ round($staff->annual_holiday,1) }}</td>
            <td>{{ round($staff->remaining_annual_holiday,1) }}</td>
            @if ($staff->lieu != null)
              <td>{{ round($staff->lieu->total_time,1) }}</td>
              <td>{{ round($staff->lieu->remaining_time,1) }}</td>
            @else
              <td></td>
              <td></td>
            @endif
            <td>
              <a href="{{ route('staffs.show',$staff->id) }}" class="btn btn-info">详情</a>
              <a href="{{ route('staffs.edit',$staff->id) }}" class="btn btn-primary">编辑信息</a>
              <a href="{{ route('staffs.edit_work_time',$staff->id) }}" class="btn btn-primary">修改排班</a>
              @if ($staff->lieu == null && count($staff->extraWorks)==0 && count($staff->absences)==0 && count($staff->totalAttendances)==0 && $staff->teacher_id == null)
              <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                操作 <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                  <form action="{{ route('staffs.leave', $staff->id) }}" method="POST">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-link" type="button" onclick="delcfm();">员工离职</button>
                  </form>
                  <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-link" type="button" onclick="delcfm();">删除</button>
                  </form>
                </ul>
              </div>
              @else
              <form action="{{ route('staffs.leave', $staff->id) }}" method="POST" style="display: inline-block;">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">员工离职</button>
              </form>
              @endif
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

@if (count($staffs)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif

<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  }

</script>

@stop
