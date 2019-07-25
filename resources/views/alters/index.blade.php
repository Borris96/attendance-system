@extends('layouts.default')
@section('title','换课信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="GET">
    课程名称
    <input type="text" name="lesson_name" id="lesson_name"class="abc input-default" placeholder="" value="{{ old('lesson_name') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('alters.create',array('term_id'=>$term_id)) }}" role="button">新增换课</a>
    <a class="btn btn-success" href="{{ route('alters.one_time',array('term_id'=>$term_id)) }}" role="button">一键换课</a>
</form>

<form class="form-inline definewidth m20" action="{{route('alters.index')}}" method="GET">
    当前学期
    <select name="term_id" id="term_id">
      @foreach ($terms as $term)
      <option value="{{$term->id}}"
      @if ($term_id == $term->id)
      selected
      @endif
      >{{ $term->term_name }}</option>
      @endforeach
    </select>&nbsp;&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">选择学期</button>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>课程名称</th>
        <th>原上课时间</th>
        <th>调整日期</th>
        <th>教室</th>
        <th>时长</th>
        <th>上课老师</th>
        <th>操作</th>
    </tr>
    </thead>
    @if (count($alters) != 0)
    <tbody id="pageInfo">
      @foreach ($alters as $a)
       <tr>
            <td>{{ $a->lesson->lesson_name }}</td>
            <td>{{ $a->lesson_date}},&nbsp;{{$a->lesson->day}}-{{ date('H:i',strtotime($a->lesson->start_time))}}-{{ date('H:i',strtotime($a->lesson->end_time)) }}</td>
            <td>{{ $a->alter_date}} </td>
            <td>{{ $a->lesson->classroom }}</td>
            <td>{{ $a->duration}}</td>
            <td>{{ $a->teacher->staff->englishname }}</td>
            <td>
                <a href="{{ route('alters.edit',array($a->id, 'term_id'=>$term_id)) }}" class="btn btn-primary">编辑换课</a>
                <form action="{{ route('alters.destroy',$a->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">删除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
</table>
@include('shared._nothing')
@endif

@if (count($alters)>config('page.PAGE_SIZE'))
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
