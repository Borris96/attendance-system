<!DOCTYPE html>
<html>
<head>
    <title>JadeClass-新增员工</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-responsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
<!--     <script type="text/javascript" src="../Js/jquery.sorted.js"></script> -->
    <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ckform.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>



    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }


    </style>
</head>
<body>
<form action="{{ route('staffs.index') }}" method="post" class="definewidth m20">
<table class="table table-bordered table-hover definewidth m10">
    <tr>
        <td width="10%" class="tableleft">员工姓名</td>
        <td><input type="text" name="username"/></td>
    </tr>
    <tr>
        <td class="tableleft">英文名</td>
        <td><input type="text" name="englishname"/></td>
    </tr>
    <tr>
        <td class="tableleft">员工编号</td>
        <td><input type="text" name="staff_id"/></td>
    </tr>
    <tr>
        <td class="tableleft">所属部门</td>
         <td>
          <select name="department">
<!--             <option disabled="disabled">请选择</option> -->
            <option value="renshi">人事部</option><option value='jiaocai'>教材部</option><option value='kefu'>客服部</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">当前职位</td>
         <td>
          <select name="position">
<!--             <option disabled="disabled">请选择</option> -->
            <option value="wenyuan">文员</option><option value='jingli'>经理</option><option value='shixisheng'>实习生</option>
          </select>
        </td>
    </tr>
    <tr>
        <td class="tableleft">入职日期</td>
        <td>
          <input type="date" name="join_company" id="date-picker"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">参加工作</td>
        <td>
          <input type="date" name="join_work" id="date-picker"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">应上下班时间</td>
        <td>
          <input type="time" name="work_time" value="09:00"/> &nbsp;至&nbsp; <input type="time" name="home_time" value="18:00"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">工作日</td>
        <td>
          <label for="mon" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="mon"/>周一</label>
          &nbsp;&nbsp;
          <label for="tue" style="display: inline-block;"><input type="checkbox" name="day" value="Tuesday" id="tue"/>周二</label>
          &nbsp;&nbsp;
          <label for="wed" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="wed"/>周三</label>
          &nbsp;&nbsp;
          <label for="thu" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="thu"/>周四</label>
          &nbsp;&nbsp;
          <label for="fri" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="fri"/>周五</label>
          &nbsp;&nbsp;
          <label for="sat" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="sat"/>周六</label>
          &nbsp;&nbsp;
          <label for="sun" style="display: inline-block;"><input type="checkbox" name="day" value="Monday" id="sun"/>周日</label>
          &nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td class="tableleft">年假天数</td>
        <td><input type="text" name="annual_holidays"/></td> <!-- 需要自动计算 -->
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button type="submit" class="btn btn-primary" type="button">提交</button> &nbsp;&nbsp;<a class="btn btn-success" href="{{ route('staffs.index') }}" role="button">返回列表</a>
        </td>
    </tr>
</table>
</form>
</body>
</html>
<script>
  var defaultDate = document.querySelectorAll('#date-picker');
  for (var i = 0; i<defaultDate.length; i++) {
    defaultDate[i].valueAsDate = new Date();
  }
</script>
