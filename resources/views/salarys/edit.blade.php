@extends('layouts.default')
@section('title','编辑时薪')
@section('content')
<div class="container">
@include('shared._messages')
@include('shared._errors')
<form action="{{ route('salarys.update',$salary->id) }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
    <tr>
        <td class="tableleft">时薪类型*</td>
         <td>
          <input type="text" name="salary_type" value="{{ $salary->salary_type }}">
        </td>
    </tr>
    <tr>
        <td class="tableleft">每小时薪资*</td>
        <td>
          <input type="text" name="salary" value="{{ $salary->salary }}"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">备注</td>
        <td>
          <textarea name="note" id="" rows="5" placeholder="如有需要请备注" >{{ $salary->note }}</textarea>
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
</div>

<script>

</script>

@stop
