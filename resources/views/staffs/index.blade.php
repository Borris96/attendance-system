@extends('layouts.default')
@section('title','员工信息')
@section('content')
@include('shared._messages')
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
        <th>参加工作年数</th>
        <th>年假天数</th>
        <th>剩余天数</th>
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
            <td>
                <a href="{{ route('staffs.show',$staff->id) }}">详情</a> |
                <a href="{{ route('staffs.edit',$staff->id) }}">编辑</a> |

                <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" style="display: inline-block;" id="myDelete">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <a href="" title="删除" onclick="delcfm();document.getElementById('myDelete').submit();return false;">删除</a>
                </form>
            </td>
        </tr>
      @endforeach
</table>

{{ $staffs->links() }} <!-- show paginate -->


<script>

  function delcfm() {
      if (!confirm("确认要删除？")) {
          window.event.returnValue = false;
      }
  }

</script>


@stop
