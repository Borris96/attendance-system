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
            <select name="departments" value="{{$staff->department_name}}">
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
            <input type="date" name="join_company" value="{{$staff->join_company}}" max="{{ date('Y-m-d') }}" disabled/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">参加工作年数</td>
          <td>
            <input type="text" name="work_year" placeholder="" value="{{$staff->work_year}}" disabled/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">应上下班时间*</td>
          <td>
            <input type="time" name="work_time" value="{{$staff->work_time}}"/> &nbsp;至&nbsp; <input type="time" name="home_time" value="{{$staff->home_time}}"/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">工作日*</td>
          <td>
            @if (!($workdays->where('workday_name','周一')->isEmpty()))
            <label for="mon" style="display: inline-block;"><input type="checkbox" name="workdays[0]" value="周一" id="mon" checked="checked" />周一</label>
            &nbsp;&nbsp;
            @else
            <label for="mon" style="display: inline-block;"><input type="checkbox" name="workdays[0]" value="周一" id="mon"/>周一</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周二')->isEmpty()))
            <label for="tue" style="display: inline-block;"><input type="checkbox" name="workdays[1]" value="周二" id="tue" checked="checked"/>周二</label>
            &nbsp;&nbsp;
            @else
            <label for="tue" style="display: inline-block;"><input type="checkbox" name="workdays[1]" value="周二" id="tue"/>周二</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周三')->isEmpty()))
            <label for="wed" style="display: inline-block;"><input type="checkbox" name="workdays[2]" value="周三" id="wed" checked="checked"/>周三</label>
            &nbsp;&nbsp;
            @else
            <label for="wed" style="display: inline-block;"><input type="checkbox" name="workdays[2]" value="周三" id="wed"/>周三</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周四')->isEmpty()))
            <label for="thu" style="display: inline-block;"><input type="checkbox" name="workdays[3]" value="周四" id="thu" checked="checked"/>周四</label>
            &nbsp;&nbsp;
            @else
            <label for="thu" style="display: inline-block;"><input type="checkbox" name="workdays[3]" value="周四" id="thu"/>周四</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周五')->isEmpty()))
            <label for="fri" style="display: inline-block;"><input type="checkbox" name="workdays[4]" value="周五" id="fri" checked="checked"/>周五</label>
            &nbsp;&nbsp;
            @else
            <label for="fri" style="display: inline-block;"><input type="checkbox" name="workdays[4]" value="周五" id="fri"/>周五</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周六')->isEmpty()))
            <label for="sat" style="display: inline-block;"><input type="checkbox" name="workdays[5]" value="周六" id="sat" checked="checked"/>周六</label>
            &nbsp;&nbsp;
            @else
            <label for="sat" style="display: inline-block;"><input type="checkbox" name="workdays[5]" value="周六" id="sat"/>周六</label>
            &nbsp;&nbsp;
            @endif

            @if (!($workdays->where('workday_name','周日')->isEmpty()))
            <label for="sun" style="display: inline-block;"><input type="checkbox" name="workdays[6]" value="周日" id="sun" checked="checked"/>周日</label>
            &nbsp;&nbsp;
            @else
            <label for="sun" style="display: inline-block;"><input type="checkbox" name="workdays[6]" value="周日" id="sun"/>周日</label>
            &nbsp;&nbsp;
            @endif
          </td>
      </tr>
      <tr>
          <td class="tableleft">年假天数</td>
          <td><input type="text" name="annual_holiday" placeholder="如不填写则自动计算" value="{{$staff->annual_holiday}}"/></td>
      </tr>
      <tr>
          <td class="tableleft">剩余年假天数</td>
          <td><input type="text" name="remaining_annual_holiday" placeholder="" value="{{$staff->remaining_annual_holiday}}"/></td>
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
