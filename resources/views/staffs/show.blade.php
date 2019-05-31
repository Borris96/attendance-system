@extends('layouts.default')
@section('title','员工个人信息')
@section('content')
<h4 style="margin: 20px;">员工个人信息 - {{ $staff->staffname }}</h4>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>职位</th>
    </tr>
    </thead>
       <tr>
            <td>{{ $staff->id }}</td>
            <td>{{ $staff->staffname }}</td>
            <td>{{ $staff->englishname }}</td>
            <td>{{ $staff->department_name }}</td>
            <td>{{ $staff->position_name }}</td>
</table>


<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>类型</th>
        <th>日期</th>
        <th>应上班</th>
        <th>应下班</th>
        <th>实上班</th>
        <th>实下班</th>
        <th>迟到（分）</th>
        <th>早退（分）</th>
        <th>请假记录</th>
        <th>加班记录</th>
        <th>总工时</th>
        <th>是否异常</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>正常</td>
            <td>04/01</td>
            <td>9:00</td>
            <td>18:00</td>
            <td>9:02</td>
            <td>18:05</td>
            <td>2</td>
            <td>0</td>
            <td>事假，16:00-18:00</td>
            <td>18:00-19:00, 未批准</td>
            <td>8小时</td>
            <td>否</td>
            <td>
                <a href="">编辑工时记录</a> | <!-- route('staffs.edit', $staff->id) -->
                <a href="">编辑请假记录</a> |
                <a href="">编辑加班记录</a>
            </td>
        </tr>
</table>

<h5 style="margin: 20px;">工资详情</h5>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>基本工时</th>
        <th>基本时薪</th>
        <th>特殊工时</th>
        <th>特殊时薪</th>
        <th>扣费</th>
        <th>该月工资</th>
    </tr>
    </thead>
       <tr>
            <td>100小时</td>
            <td>30元</td>
            <td>20小时</td>
            <td>50元</td>
            <td>100元</td>
            <td>1000元</td>
</table>

@stop
