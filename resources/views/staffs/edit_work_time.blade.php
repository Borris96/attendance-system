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

  window.onload=function()
  {
    var oT=document.getElementById('card');
    oT.onkeydown=function(ev)
    {
      var oW=oT.value;
      var oEvent=ev||event;
      if(oEvent.keyCode==8)
      {
        if(oW)
        {
          for(var i=0;i<oW.length;i++)
          {
            var newStr=oW.replace(/\s$/g,'');
          }
          oT.value=newStr
        }
      }else{
        for(var i=0;i<oW.length;i++)
        {
          var arr=oW.split('');

          if((i+1)%5==0)
          {
            arr.splice(i,0,' ');
          }
        }
        oT.value=arr.join('');
      }
    }
  }
</script>

@stop
