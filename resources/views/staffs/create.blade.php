@extends('layouts.default')
@section('title','新增员工')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="{{ route('staffs.store') }}" method="post" class="definewidth m20">
  {{ csrf_field() }}
  <table class="table table-bordered table-hover definewidth m10">
      <tr>
          <td width="10%" class="tableleft">员工姓名*</td>
          <td><input type="text" name="staffname" value="{{ old('staffname') }}"/></td>
      </tr>
      <tr>
          <td class="tableleft">英文名</td>
          <td><input type="text" name="englishname" value="{{ old('englishname') }}"/></td>
      </tr>
      <tr>
          <td class="tableleft">员工编号*</td>
          <td><input type="text" name="id" value="{{ old('id') }}"/></td>
      </tr>
      <tr>
          <td class="tableleft">所属部门</td>
           <td>
            <select name="departments">
              @foreach ($departments as $d)
  <!--             <option disabled="disabled">请选择</option> -->
              <option value="{{$d->id}}">{{ $d->department_name }}</option>

              @endforeach
            </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft">当前职位</td>
           <td>
            <select name="positions">
  <!--             <option disabled="disabled">请选择</option> -->
              @foreach ($positions as $p)
  <!--             <option disabled="disabled">请选择</option> -->
              <option value="{{$p->id}}">{{ $p->position_name }}</option>

              @endforeach
            </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft" >入职日期</td>
          <td>
            <input type="date" name="join_company" id="date-picker" value="{{ old('join_company') }}"/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">参加工作</td>
          <td>
            <input type="date" name="join_work"/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">应上下班时间*</td>
          <td>
            <input type="time" name="work_time" value="09:00"/> &nbsp;至&nbsp; <input type="time" name="home_time" value="18:00"/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">工作日*</td>
          <td>
            <label for="mon" style="display: inline-block;"><input type="checkbox" name="workdays[0]" value="Monday" id="mon"/>周一</label>
            &nbsp;&nbsp;
            <label for="tue" style="display: inline-block;"><input type="checkbox" name="workdays[1]" value="Tuesday" id="tue"/>周二</label>
            &nbsp;&nbsp;
            <label for="wed" style="display: inline-block;"><input type="checkbox" name="workdays[2]" value="Wednesday" id="wed"/>周三</label>
            &nbsp;&nbsp;
            <label for="thu" style="display: inline-block;"><input type="checkbox" name="workdays[3]" value="Thursday" id="thu"/>周四</label>
            &nbsp;&nbsp;
            <label for="fri" style="display: inline-block;"><input type="checkbox" name="workdays[4]" value="Friday" id="fri"/>周五</label>
            &nbsp;&nbsp;
            <label for="sat" style="display: inline-block;"><input type="checkbox" name="workdays[5]" value="Saturday" id="sat"/>周六</label>
            &nbsp;&nbsp;
            <label for="sun" style="display: inline-block;"><input type="checkbox" name="workdays[6]" value="Sunday" id="sun"/>周日</label>
            &nbsp;&nbsp;
          </td>
      </tr>
      <tr>
          <td class="tableleft">年假天数</td>
          <td><input type="text" name="annual_holiday" value="5"/></td> <!-- 需要自动计算 -->
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
