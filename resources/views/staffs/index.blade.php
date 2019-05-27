<!DOCTYPE html>
<html>
<head>
    <title>JadeClass-员工信息</title>
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
<form class="form-inline definewidth m20" action="index.html" method="get">
    员工姓名：
    <input type="text" name="username" id="username"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <a class="btn btn-success" href="{{ route('staffs.create') }}" role="button">新增员工</a>

</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>员工编号</th>
        <th>员工姓名</th>
        <th>英文名</th>
        <th>所属部门</th>
        <th>当前职位</th>
        <th>入职日期</th>
        <th>参加工作</th>
        <th>操作</th>
    </tr>
    </thead>
       <tr>
            <td>001</td>
            <td>张三</td>
            <td>Jack</td>
            <td>教材部</td>
            <td>文员</td>
            <td>2017/07/01</td>
            <td>2015/06/01</td>
            <td>
                <a href="">详情</a> | <!-- route('staffs.show', $staff->id) -->
                <a href="">编辑</a> | <!-- route('staffs.edit', $staff->id) -->
                <a href="">删除</a> <!-- route('staffs.destroy', $staff->id) -->
            </td>
        </tr>
</table>
</body>
</html>
<script>

  function del(id)
  {


    if(confirm("确定要删除吗？"))
    {

      var url = "index.html";

      window.location.href=url;

    }




  }
</script>
