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
    <!-- <a href="{{route('leave_teachers.index')}}" class="btn btn-info">查看离职老师</a> -->
</form>

<form class="form-inline definewidth m20" action="{{route('teachers.index')}}" method="GET">
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
    <button type="submit" class="btn btn-info">选择学期</button>
    &nbsp;&nbsp;
    <a class="btn btn-primary" href="{{ route('edit_term',array('term_id'=>$term_id)) }}" role="button">修改当前学期</a>
    <!-- <a class="btn btn-primary" href="" role="button" disabled>修改当前学期</a> -->
    &nbsp;&nbsp;
    <a class="btn btn-success" href="{{ route('create_term',array('term_id'=>$term_id)) }}" role="button">新增学期</a>
</form>
@if ($term_id != null)
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>老师编号</th>
        <th>英文名</th>
        <th>本学期课程</th>
        <th>累计缺课时(学期)</th>
        <th>累计代课时(学期)</th>
        <th>操作</th>
    </tr>
    </thead>
    @if (count($teachers) != 0)
    <tbody id="pageInfo">
        @foreach ($teachers as $t)
       <tr>
            <td>{{$t->staff->id}}</td>
            <td>{{$t->staff->englishname}}</td>
            <td>
              @if (count($t->lessonUpdates) != 0)
                @foreach($t->lessonUpdates as $lu)
                @if ($lu->lesson->term_id == $term_id)
                  @if ($flag)
                    @if ($lu->day == 'Mon')
                    <span style="font-weight: bold;">{{ $lu->lesson->lesson_name }}</span>
                    @endif
                  @else
                  <span style="font-weight: bold;">{{ $lu->lesson->lesson_name }}</span>
                  @endif

                @endif
                @endforeach
              @else
              无
              @endif
            </td>
            <td>
              @if ($t->termTotals != null)
                @foreach ($t->termTotals as $tt)
                  @if ($tt->term_id == $term_id)
                    {{ $tt->total_missing_hours }}
                  @endif
                @endforeach
              @endif
            </td>
            <td>
              @if ($t->termTotals != null)
                @foreach ($t->termTotals as $tt)
                  @if ($tt->term_id == $term_id)
                    {{ $tt->total_substitute_hours }}
                  @endif
                @endforeach
              @endif
            </td>

            <td>
                <a href="{{ route('teachers.show',array($t->id,'term_id'=>$term_id)) }}" class="btn btn-info">查看详情</a>
                @if ($t->status == true)
                <a href="{{ route('teachers.edit',array($t->id,'term_id'=>$term_id)) }}" class="btn btn-primary">关联课程</a>
                <form action="{{ route('teachers.remove',$t->id) }}" method="POST" style="display: inline-block;">
                  {{ method_field('PATCH') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-warning" type="button" onclick="delcfm();">老师离职</button>
                </form>
                @else
                <button type="" class="btn btn-primary" type="button" disabled="">关联课程</button>
                <button type="" class="btn btn-danger" type="button" disabled="">已离职</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
</table>
@include('shared._nothing')
@endif

@if (count($teachers)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif

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
