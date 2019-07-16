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
        <th>入职日期</th>
        <th>离职日期</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="pageInfo">
        @foreach ($teachers as $t)
       <tr>
            <td>{{$t->staff->id}}</td>
            <td>{{$t->staff->englishname}}</td>
            <td>{{$t->join_date}}</td>
            <td>{{$t->leave_date}}</td>
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
