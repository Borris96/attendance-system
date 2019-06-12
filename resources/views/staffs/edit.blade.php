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
          <td class="tableleft">当前职位*</td>
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
            @for($i=0;$i<=6;$i++)
            周{{$days[$i]}}：
              <input type="time" name="work_time[{{$i}}]" value="{{ $workdays[$i]->work_time }}"/>
             &nbsp;至&nbsp;
             <input type="time" name="home_time[{{$i}}]" value="{{ $workdays[$i]->home_time }}"/> <br>
            @endfor
          </td>
      </tr>

      <tr>
          <td class="tableleft">工作经历</td>
          <td>
            @for($i=0;$i<=9;$i++)
              @if ($i<$count)
              <input type="date" name="work_experiences[{{$i}}]" value="{{ $work_historys[$i]->work_experience }}"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[{{$i}}]" value="{{ $work_historys[$i]->leave_experience }}"/> <br>
              @else
              <input type="date" name="work_experiences[{{$i}}]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[{{$i}}]"/> <br>
              @endif
            @endfor
          </td>
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
