@extends('layouts.default')
@section('title','新增员工')
@section('content')
@include('shared._messages')

<div class="container">
@include('shared._errors')
<form action="{{ route('staffs.store_part_time') }}" method="post" class="definewidth m20">
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
              @if (old('join_company')!=null) value="{{ old('join_company') }}" @endif
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
        <td class="tableleft">工资卡</td>
        <td><input id="card" type="text" name="card_number" value="{{old('card_number')}}" maxlength="23"></td>
      </tr>

      <tr>
        <td class="tableleft">开户行</td>
        <td><input type="text" name="bank" value="{{old('bank')}}"></td>
      </tr>

      <tr>
          <td class="tableleft"></td>
          <td>
              <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('staffs.part_time_index') }}" role="button">返回列表</a>
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
