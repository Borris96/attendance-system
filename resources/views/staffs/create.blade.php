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
          <td class="tableleft">当前职位</td>
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
            <input type="date" name="join_company" min="{{ date('Y').'-01-01' }}" max="{{ date('Y-m-d') }}"
              @if (old('join_company')!=null) value="{{ date('Y-m-d',strtotime(old('join_company'))) }}"
              @endif
            />
          </td>
      </tr>

<!--       <tr>
          <td class="tableleft">工作经历*</td>
          <td>
            <input type="date" name="work_experiences[0]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[0]"/> <br>
            <input type="date" name="work_experiences[1]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[1]"/> <br>
            <input type="date" name="work_experiences[2]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[2]"/> <br>
            <input type="date" name="work_experiences[3]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[3]"/> <br>
            <input type="date" name="work_experiences[4]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[4]"/> <br>
            <input type="date" name="work_experiences[5]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[5]"/> <br>
            <input type="date" name="work_experiences[6]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[6]"/> <br>
            <input type="date" name="work_experiences[7]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[7]"/> <br>
            <input type="date" name="work_experiences[8]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[8]"/> <br>
            <input type="date" name="work_experiences[9]"/> &nbsp;至&nbsp; <input type="date" name="leave_experiences[9]"/> <br>
          </td>
      </tr> -->


      <tr>
          <td class="tableleft">参加工作年数*</td>
          <td>
            <input type="text" name="work_year" placeholder="" value="{{ old('work_year') }}"/>
          </td>
      </tr>
      <tr>
          <td class="tableleft">应上下班时间*</td>
          <td>
            周一：
            <input type="time" name="work_time[0]"
            @if (old('work_time[0]')!=null) value="{{ old('work_time[0]') }}"
            @else value="09:00"
            @endif
           />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[0]"
            @if (old('home_time[0]')!=null) value="{{ old('home_time[0]') }}"
            @else value="18:00"
            @endif
           /> <br>
            周二：
            <input type="time" name="work_time[1]"
            @if (old('work_time[1]')!=null) value="{{ old('work_time[1]') }}"
            @else value="09:00"
            @endif
           />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[1]"
            @if (old('home_time[1]')!=null) value="{{ old('home_time[1]') }}"
            @else value="18:00"
            @endif
           /> <br>
            周三：
            <input type="time" name="work_time[2]"
            @if (old('work_time[2]')!=null) value="{{ old('work_time[2]') }}"
            @else value="09:00"
            @endif
           />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[2]"
            @if (old('home_time[2]')!=null) value="{{ old('home_time[2]') }}"
            @else value="18:00"
            @endif
           /> <br>
            周四：
            <input type="time" name="work_time[3]"
            @if (old('work_time[3]')!=null) value="{{ old('work_time[3]') }}"
            @else value="09:00"
            @endif
           />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[3]"
            @if (old('home_time[3]')!=null) value="{{ old('home_time[3]') }}"
            @else value="18:00"
            @endif
           /> <br>
            周五：
            <input type="time" name="work_time[4]"
            @if (old('work_time[4]')!=null) value="{{ old('work_time[4]') }}"
            @else value="09:00"
            @endif
           />
           &nbsp;至&nbsp;
           <input type="time" name="home_time[4]"
            @if (old('home_time[4]')!=null) value="{{ old('home_time[4]') }}"
            @else value="18:00"
            @endif
           /> <br>
            周六：
            <input type="time" name="work_time[5]" value="{{ old('work_time[5]') }}"/>
           &nbsp;至&nbsp;
           <input type="time" name="home_time[5]" value="{{ old('home_time[5]') }}"/> <br>
            周日：
            <input type="time" name="work_time[6]" value="{{ old('work_time[6]') }}"/>
           &nbsp;至&nbsp;
           <input type="time" name="home_time[6]" value="{{ old('home_time[6]') }}"/> <br>
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
