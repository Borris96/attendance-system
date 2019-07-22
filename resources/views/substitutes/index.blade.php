@extends('layouts.default')
@section('title','代课信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="GET">
    课程名称
    <input type="text" name="lesson_name" id="lesson_name"class="abc input-default" placeholder="" value="{{ old('lesson_name') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('substitutes.create',array('term_id'=>$term_id)) }}" role="button">新增代课缺课</a>
</form>

<form class="form-inline definewidth m20" action="{{route('substitutes.index')}}" method="GET">
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
        <th>上课时间</th>
        <th>教室</th>
        <th>学期</th>
        <th>时长</th>
        <th>原老师</th>
        <th>代课老师</th>
        <th>操作</th>
    </tr>
    </thead>
    @if (count($substitutes) != 0)
    <tbody id="pageInfo">
      @foreach ($substitutes as $s)
       <tr>
            <td>{{ $s->lesson->lesson_name }}</td>
            <td>{{$s->lesson_date}}&nbsp;{{$s->lesson->day}}-{{ date('H:i',strtotime($s->lesson->start_time))}}-{{ date('H:i',strtotime($s->lesson->end_time)) }}</td>
            <td>{{ $s->lesson->classroom }}</td>
            <td>{{ $s->term->term_name }}</td>
            <td>{{ $s->lesson->duration }}</td>
            <td>{{ $s->teacher->staff->englishname }}</td>
            @if ($s->subTeacher != null)
            <td>{{ $s->subTeacher->staff->englishname }}</td>
            @else
            <td></td>
            @endif
            <td>
                <a href="{{ route('substitutes.edit',array($s->id,'term_id'=>$term_id)) }}" class="btn btn-primary">编辑</a>
                <form action="{{ route('substitutes.destroy',array($s->id,'term_id'=>$term_id)) }}" method="POST" style="display: inline-block;">
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

@if (count($substitutes)>config('page.PAGE_SIZE'))
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
