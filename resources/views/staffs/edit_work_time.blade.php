@extends('layouts.default')
@section('title','编辑员工')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="{{ route('staffs.update_work_time', $staff->id) }}" method="post" class="definewidth m20">
  {{ method_field('PATCH') }}
  {{ csrf_field() }}
  <table class="table table-bordered table-hover definewidth m10">
      <tr>
          <td width="10%" class="tableleft">员工姓名</td>
          <td>{{ $staff->staffname }}</td>
      </tr>
      <tr>
          <td class="tableleft">英文名</td>
          <td>{{$staff->englishname}}</td>
      </tr>
      <tr>
          <td class="tableleft">员工编号*</td>
          <td>{{ $staff->id }}</td>
      </tr>
      <tr>
          <td class="tableleft">所属部门</td>
           <td>
            {{ $staff->department_name}}
          </td>
      </tr>
      <tr>
          <td class="tableleft">当前职位*</td>
           <td>
            {{ $staff->position_name}}
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
          <td class="tableleft">生效日期*</td>
           <td>
            <input type="date" name="effective_date" value="old('effective_date')">
          </td>
      </tr>

      <tr>
          <td class="tableleft"></td>
          <td>
              <button type="submit" class="btn btn-primary" type="button" onclick="sure()">提交</button> &nbsp;&nbsp;
              @if ($staff_id != null)
              <a class="btn btn-success" href="{{ route('staffs.part_time_index') }}" role="button">返回列表</a>
              @else
              <a class="btn btn-success" href="{{ route('staffs.index') }}" role="button">返回列表</a>
              @endif
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

  function sure() {
      if (!confirm("提交后该段时间的排班记录不可更改，时间是否无误？")) {
          window.event.returnValue = false;
      }
  }
</script>

@stop
