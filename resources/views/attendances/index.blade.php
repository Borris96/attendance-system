@extends('layouts.default')
@section('title','考勤信息')
@section('content')

<form class="form-inline definewidth m20" action="{{ route('attendances.index') }}" method="get">
    员工姓名：
    <input type="text" name="staffname" id="staffname"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;

    <select name="name" id="name_select">
        <option value="March">三月</option>
        <option value="April">四月</option>
        <option value="May">五月</option>
    </select>
    &nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>

</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>职位</th>
        <th>总应工时</th>
        <th>总实际工时</th>
        <th>总基本工时</th>
        <th>总加班工时</th>
        <th>总迟到</th>
        <th>总早退</th>
        <th>总请假时长</th>
        <th>工时差值</th>
        <th>是否异常</th>
        <th>当月工资</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>001</td>
            <td>张三</td>
            <td>Jack</td>
            <td>教材部</td>
            <td>文员</td>
            <td>160小时</td>
            <td>150小时</td>
            <td>148小时</td>
            <td>2小时</td>
            <td>2小时，2次</td>
            <td>2小时，2次</td>
            <td>事假：2小时，年假：8小时</td>
            <td>0小时</td>
            <td>否</td>
            <td>5000.00元</td>
            <td>
                <a href="{{ route('fakeshow') }}">查看</a> <!-- route('staffs.edit', $staff->id) -->
            </td>
        </tr>
</table>

@stop
