@extends('layouts.default')
@section('title','编辑员工')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="{{ route('staffs.update', $staff->id) }}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
  <table class="table table-bordered table-hover definewidth m10">
      <tr>
          <td width="10%" class="tableleft">员工姓名*</td>
          <td><input type="text" name="staffname" value="{{ $staff->staffname }}" disabled/></td>
      </tr>
      <tr>
          <td class="tableleft">英文名</td>
          <td><input type="text" name="englishname" value="{{$staff->englishname}}" disabled/></td>
      </tr>
      <tr>
          <td class="tableleft">员工编号*</td>
          <td><input type="text" name="id" value="{{ $staff->id }}" disabled/></td>
      </tr>
      <tr>
          <td class="tableleft">所属部门</td>
           <td>
            <select name="departments">
              <option value="">----请选择----</option>
              @foreach ($departments as $d)
                @if (($staff->department_name)===$d->department_name)
                <option value="{{$d->id}}" selected="selected">{{ $d->department_name }}</option>
                @else
                <option value="{{$d->id}}">{{ $d->department_name }}</option>
                @endif
              @endforeach
            </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft">当前职位</td>
           <td>
            <select name="positions">
              <option value="">----请选择----</option>
              @foreach ($positions as $p)
                @if (($staff->position_name)===$p->position_name)
                <option value="{{$p->id}}" selected="selected"> {{ $p->position_name }}</option>
                @else
                <option value="{{$p->id}}">{{ $p->position_name }}</option>
                @endif
              @endforeach
            </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft" >入职日期</td>
          <td>
            <input type="date" name="join_company" value="{{$staff->join_company}}" min="" max="{{ date('Y-m-d') }}" disabled />
          </td>
      </tr>
      <tr>
          <td class="tableleft">应上下班时间*</td>
          <td>
            周一：
            <input type="time" name="work_time[0]" value="{{ $workdays[0]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[0]" value="{{ $workdays[0]->home_time }}"/> <br>
            周二：
            <input type="time" name="work_time[1]" value="{{ $workdays[1]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[1]" value="{{ $workdays[1]->home_time }}"/> <br>
            周三：
            <input type="time" name="work_time[2]" value="{{ $workdays[2]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[2]" value="{{ $workdays[2]->home_time }}"/> <br>
            周四：
            <input type="time" name="work_time[3]" value="{{ $workdays[3]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[3]" value="{{ $workdays[3]->home_time }}"/> <br>
            周五：
            <input type="time" name="work_time[4]" value="{{ $workdays[4]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[4]" value="{{ $workdays[4]->home_time }}"/> <br>
            周六：
            <input type="time" name="work_time[5]" value="{{ $workdays[5]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[5]" value="{{ $workdays[5]->home_time }}"/> <br>
            周日：
            <input type="time" name="work_time[6]" value="{{ $workdays[6]->work_time }}" />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[6]" value="{{ $workdays[6]->home_time }}"/> <br>
          </td>
      </tr>

      </tr>

      <tr>
          <td class="tableleft"></td>
          <td>
              <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('staffs.index') }}" role="button">返回列表</a>
          </td>
      </tr>
  </table>
</form>

</div>



<script>
  var defaultDate = document.querySelectorAll('#date-picker');
  for (var i = 0; i<defaultDate.length; i++) {
    defaultDate[i].valueAsDate = new Date();
  }
</script>

@stop
