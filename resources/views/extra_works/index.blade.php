@extends('layouts.default')
@section('title','加班信息')
@section('content')
@include('shared._messages')
<form class="form-inline definewidth m20" action="{{ route('extra_works.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <a class="btn btn-success" href="{{ route('extra_works.create') }}" role="button">新增加班</a>

</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>加班类型</th>
        <th>加班时间</th>
        <th>时长</th>
        <th>是否批准</th>
        <th>备注</th>
        <th>创建日期</th>
        <th>上次修改</th>
        <th>操作</th>
    </tr>
    </thead>
      @foreach ($extra_works as $ew)
       <tr>
            <td> {{$ew->staff->id}} </td>
            <td> {{$ew->staff->staffname}} </td>
            <td> {{$ew->staff->englishname}} </td>
            <td> {{$ew->staff->department_name}} </td>
            <td> {{$ew->extra_work_type}} </td>
            <td> {{$ew->extra_work_start_time}} 至 {{$ew->extra_work_end_time}} </td>
            <td> {{$ew->duration}} </td>
            <td>
              @if ($ew->approve == true) 是
              @else 否
              @endif
            </td>
            <td> {{$ew->note}} </td>
            <td> {{$ew->created_at}} </td>
            <td> {{$ew->updated_at}} </td>
            <td>
                <a href="{{route('extra_works.edit',$ew->id)}}" class="btn btn-primary">编辑</a>
                <form action="{{ route('extra_works.destroy', $ew->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger" type="button" onclick="delcfm();">删除</button>
                </form>
            </td>
        </tr>
      @endforeach
</table>

<script>

  function delcfm() {
      if (!confirm("删除记录可能影响考勤结果，确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
