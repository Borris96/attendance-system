@extends('layouts.default')
@section('title','老师信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="{{ route('teachers.role') }}" method="POST">
  {{csrf_field()}}
    英文姓名
    <select data-placeholder="选择员工..." class="chosen-select" name="staff_ids[]" id="select-staff" multiple>
      @foreach ($staffs as $staff)
      <option value="{{ $staff->id }}">{{ $staff->englishname }}</option>
      @endforeach
    </select>
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-success">新增老师</button>
    &nbsp;&nbsp;
    <a href="{{route('leave_teachers.index')}}" class="btn btn-info">查看离职老师</a>
</form>

<form class="form-inline definewidth m20" action="" method="GET">
    当前学期
    <select name="term_id" id="term_id">
      @foreach ($terms as $term)
      <option value="{{$term->id}}"
      @if (old('term_id') == $term->id)
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
        <th>老师编号</th>
        <th>英文名</th>
        <th>本月应排课</th>
        <th>本月实际排课</th>
        <th>累计缺课时(学期)</th>
        <th>累计代课时(学期)</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
        @foreach ($teachers as $t)
       <tr>
            <td>{{$t->staff->id}}</td>
            <td>{{$t->staff->englishname}}</td>
            <td>还没算呢</td>
            <td>还没算呢</td>
            @if ($t->total_missing_hours != null)
            <td>{{$t->total_missing_hours}}小时</td>
            @else
            <td>0小时</td>
            @endif
            @if ($t->total_substitute_hours != null)
            <td>{{$t->total_substitute_hours}}小时</td>
            @else
            <td>0小时</td>
            @endif
            <td>
                <a href="{{ route('teachers.show',$t->id) }}" class="btn btn-info">查看详情</a>
                <a href="{{ route('teachers.edit',$t->id) }}" class="btn btn-primary">关联课程</a>
                <form action="{{ route('teachers.remove',$t->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('PATCH') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">移除老师</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if (count($teachers)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif

<script>

  function delcfm() {
      if (!confirm("确认操作？")) {
          window.event.returnValue = false;
      }
  };

  $(function(){
      $('.chosen-select').chosen({no_results_text: "Oops, nothing found!"});
  });
</script>

@stop
