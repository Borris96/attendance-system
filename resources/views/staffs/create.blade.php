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
          <td class="tableleft">英文名*</td>
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
              <option value="">----请选择----</option>
              @foreach ($departments as $d)
  <!--             <option disabled="disabled">请选择</option> -->
              <option value="{{$d->id}}" @if(old('departments') == $d->id) selected @endif > {{ $d->department_name }} </option>

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
  <!--             <option disabled="disabled">请选择</option> -->
              <option value="{{$p->id}}" @if(old('positions') == $p->id) selected @endif >{{ $p->position_name }}</option>

              @endforeach
            </select>
          </td>
      </tr>
      <tr>
          <td class="tableleft" >入职日期*</td>
          <td>
            <input type="date" name="join_company"
              @if (old('join_company')!=null) value="{{ date('Y-m-d',strtotime(old('join_company'))) }}"
              @endif
            />
          </td>
      </tr>

      <tr>
          <td class="tableleft">应上下班时间</td>
          <td>
            @for($i=0;$i<=4;$i++)
            周{{$days[$i]}}：
              <input type="time" name="work_time[{{$i}}]" value="09:00"/>
             &nbsp;至&nbsp;
             <input type="time" name="home_time[{{$i}}]" value="18:00"/> <br>
            @endfor
            @for($i=5;$i<=6;$i++)
            周{{$days[$i]}}：
              <input type="time" name="work_time[{{$i}}]" value=""/>
             &nbsp;至&nbsp;
             <input type="time" name="home_time[{{$i}}]" value=""/> <br>
            @endfor
          </td>
      </tr>

      <tr>
          <td class="tableleft">工作经历</td>
          <td>
            @for($i=0;$i<=9;$i++)
              <input type="date" name="work_experiences[{{$i}}]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[{{$i}}]"/> <br>
            @endfor
          </td>
      </tr>

      <tr>
          <td class="tableleft">年假小时数</td>
          <td><input type="text" name="annual_holiday" placeholder="如不填写则自动计算" value="{{ old('annual_holiday') }}" /></td>
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
