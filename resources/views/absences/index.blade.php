@extends('layouts.default')
@section('title','请假信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="{{ route('absences.index') }}" method="get">
    员工英文名
    <input type="text" name="englishname" id="englishname"class="abc input-default" placeholder="" value="{{ old('englishname') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('absences.create') }}" role="button">新增请假</a>

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
    <tbody id="pageInfo">
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
                <a href="{{route('absences.edit',$absence->id)}}" class="btn btn-primary">编辑</a> <!-- route('staffs.edit', $staff->id) -->

                <form action="{{ route('absences.destroy', $absence->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger" type="button" onclick="delcfm();">删除</button>
                </form>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

@if (count($absences)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif
<script>

  function delcfm() {
      if (!confirm("删除记录可能影响考勤结果，确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
