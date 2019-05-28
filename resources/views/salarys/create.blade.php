@extends('layouts.default')
@section('title','新增时薪')
@section('content')
<form action="{{ route('holidays.index') }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td class="tableleft">时薪类型</td>
         <td>
          <select name="salary_types">
<!--             <option disabled="disabled">请选择</option> -->
            <option value='quanzhi'>全职</option>
            <option value="jianzhi">兼职</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">薪资</td>
        <td>
          <input type="text" name="salary"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder="如有需要请备注"></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('salarys.index') }}" role="button">返回列表</a>
        </td>
    </tr>
</table>
</form>

<script>

</script>

@stop
