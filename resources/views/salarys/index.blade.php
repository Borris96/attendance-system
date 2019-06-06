@extends('layouts.default')
@section('title','时薪信息')
@section('content')
@include('shared._messages')

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
      @foreach ($salarys as $s)
       <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->salary_type }}</td>
            <td>{{ $s->salary }}</td>
            <td>{{ $s->note }}</td>
            <td>
                <a href="{{ route('salarys.edit',$s->id) }}" class="btn btn-primary">编辑</a>

                <form action="{{ route('salarys.destroy', $s->id) }}" method="POST" style="display: inline-block;">
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
      if (!confirm("确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
