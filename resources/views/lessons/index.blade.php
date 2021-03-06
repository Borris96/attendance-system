@extends('layouts.default')
@section('title','课程信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="GET">
    课程名称
    <input type="text" name="lesson_name" id="lesson_name"class="abc input-default" placeholder="" value="{{ old('lesson_name') }}">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('lessons.create',array('term_id'=>$term_id)) }}" role="button">新增课程</a>
</form>

<form class="form-inline definewidth m20" action="{{route('lessons.index')}}" method="GET">
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
        <th>上课老师</th>
        <th>操作</th>
    </tr>
    </thead>
    @if (count($lessons) != 0)
    <tbody id="pageInfo">
      @foreach ($lessons as $l)
       <tr>
            <td>{{$l->lesson_name}}</td>
            @if ($l->day == 'Mon')
            <td>{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time))}}</td>
            @else
            <td>{{$l->day}}-{{ date('H:i',strtotime($l->start_time))}}-{{ date('H:i',strtotime($l->end_time)) }}</td>
            @endif
            <td>{{$l->classroom}}</td>
            <td>{{$l->term->term_name}}</td>
            <td>{{$l->duration}}</td>
            @if ($l->teacher!=null)
            <td>{{$l->teacher->staff->englishname}}</td>
            @else
            <td></td>
            @endif
            <td>
                <a href="{{ route('lessons.edit',array($l->id, 'term_id'=>$term_id)) }}" class="btn btn-primary">编辑课程</a>
                @if ($l->teacher_id != null)
                <a href="{{ route('lessons.edit_teacher',array($l->id, 'term_id'=>$term_id)) }}" class="btn btn-info">更换老师</a>
                @else
                <a href="" class="btn btn-info" disabled>更换老师</a>
                @endif
                <form action="{{ route('lessons.destroy',array($l->id, 'term_id'=>$term_id)) }}" method="POST" style="display: inline-block;">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  @if (count($l->substitutes) != 0 || count($l->alters) != 0)
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();" disabled="">删除</button>
                  @else
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">删除</button>
                  @endif

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

@if (count($lessons)>config('page.PAGE_SIZE'))
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
