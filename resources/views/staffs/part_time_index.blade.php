@extends('layouts.default')
@section('title','兼职员工信息')
@section('content')
@include('shared._messages')
<form class="form-inline definewidth m20" action="{{ route('staffs.part_time_index') }}" method="get">
    员工英文名
    <input type="text" name="englishname" id="englishname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a href="{{route('staffs.index')}}" class="btn btn-info">查看在职员工</a>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('staffs.create_part_time') }}" role="button">新建兼职员工</a>
    </ul>
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
            <td>
                <a href="{{route('staffs.show_part_time',array('id'=>$staff->id))}}" class="btn btn-info">详情</a>
                <a href="{{ route('staffs.edit_part_time',array('id'=>$staff->id)) }}" class="btn btn-primary">编辑信息</a>
                <a href="{{ route('staffs.edit_work_time',$staff->id) }}" class="btn btn-primary">修改排班</a>
                <form action="{{ route('staffs.leave', $staff->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('PATCH') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">员工离职</button>
                </form>
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
