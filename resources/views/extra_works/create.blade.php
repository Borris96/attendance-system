@extends('layouts.default')
@section('title','新增加班')
@section('content')
<form action="{{ route('extra_works.index') }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工姓名</td>
        <td>

        <select name="name" id="name_select">
            <option value="zhangsan">张三</option>
            <option value="lisi">李四</option>
            <option value="wangwu">王五</option>
        </select>

        </td>
    </tr>
    <tr>
        <td class="tableleft">加班时间</td>
        <td>
          <input type="datetime-local" name="extra_work_start_time"/> &nbsp;至&nbsp;
          <input type="datetime-local" name="extra_work_end_time" />
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否批准</td>
        <td><input type="text" name="approve"/></td> <!-- 需要自动计算 -->
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder="如修改，请备注修改原因"></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('extra_works.index') }}" role="button">返回列表</a>
        </td>
    </tr>
</table>
</form>

<script>

  $(function(){
      $('#name_select').chosen();
  });
</script>

@stop
