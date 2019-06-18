@extends('layouts.default')
@section('title','调休信息')
@section('content')
@include('shared._messages')
<form class="form-inline definewidth m20" action="" method="get">
<a class="btn btn-success" href="{{ route('holidays.create') }}" role="button">新增调休</a>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>日期</th>
        <th>类型</th>
        <th>调上班</th>
        <th>备注</th>
        <th>操作</th>
    </tr>
    </thead>
      @foreach($holidays as $h)
       <tr>
            <td>{{ $h->date }}</td>
            <td>{{ $h->holiday_type }}</td>
            <td>{{ $h->workday_name }}</td>
            <td>{{ $h->note }}</td>
            <td>
                <a href="{{route('holidays.edit',$h->id)}}" class="btn btn-primary">编辑</a>
                <form action="{{ route('holidays.destroy', $h->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger" type="button" onclick="delcfm();">删除</button>
                </form>
            </td>
        </tr>
      @endforeach
</table>
{{ $holidays->links() }}

<script>

  function delcfm() {
      if (!confirm("确认要删除？")) {
          window.event.returnValue = false;//这句话关键，没有的话，还是会执行下一步的
      }
  }

</script>

@stop
