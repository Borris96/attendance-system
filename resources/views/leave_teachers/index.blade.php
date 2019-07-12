@extends('layouts.default')
@section('title','离职老师信息')
@section('content')
@include('shared._messages')

<form class="form-inline definewidth m20" action="" method="">
    <a href="{{route('teachers.index')}}" class="btn btn-info">查看在职老师</a>
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
                <button type="submit" class="btn btn-danger" type="button" disabled="">已离职</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if (count($teachers)>config('page.PAGE_SIZE'))
@include('shared._pagination')
@endif

<script>

</script>

@stop
