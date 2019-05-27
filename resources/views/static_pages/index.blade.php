
<!DOCTYPE HTML>
<html>
<head>
    <title>JadeClass考勤管理系统</title>
    <link href="{{ asset('css/dpl-min.css') }}" rel="stylesheet" type="text/css" charset="utf-8"/>
    <link href="{{ asset('css/bui-min.css') }}" rel="stylesheet" type="text/css" charset="utf-8"/>
    <link href="{{ asset('css/main-min.css') }}" rel="stylesheet" type="text/css" charset="utf-8"/>
</head>
<body>

<div class="header">

    <div class="dl-title">
        <!--<img src="/chinapost/Public/assets/img/top.png">-->
    </div>

    <div class="dl-log">欢迎您，<span class="dl-log-user">root</span><a href="#" title="退出系统" class="dl-log-quit">[退出]</a>
    </div>
</div>
<div class="content">
    <div class="dl-main-nav">
        <div class="dl-inform"><div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div></div>
        <ul id="J_Nav"  class="nav-list ks-clear">
            <li class="nav-item dl-selected"><div class="nav-item-inner nav-home">上班考勤管理</div></li>   <li class="nav-item dl-selected"><div class="nav-item-inner nav-order">上课考勤管理</div></li>

        </ul>
    </div>
    <ul id="J_NavContent" class="dl-tab-conten">

    </ul>
</div>

<div style="text-align:center;">
    &copy; Since 2019 <a href="https://github.com/Borris96/attendance-system">JadeClass</a>
    &nbsp;|&nbsp;
    Powered by <a href="https://laravelacademy.org" title="采用emlog系统" target="_blank">Laravel</a>
</div>

<script type="text/javascript" src="{{ asset('js/jquery-1.8.1.min.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/bui-min.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/common/main-min.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/config-min.js') }}" charset="utf-8"></script>
<script>
    BUI.use('common/main',function(){
        var config = [{id:'1',menu:[{text:'上班考勤管理',items:[{id:'1',text:'员工信息管理',href:'{{ route('staffs.index') }}'},{id:'2',text:'基本信息管理',href:''},{id:'3',text:'请假信息管理',href:''},{id:'4',text:'加班信息管理',href:''},{id:'5',text:'考勤信息管理',href:''}]}]},{id:'2',homePage : '9',menu:[{text:'上课考勤管理',items:[{id:'99',text:'查询业务',href:''}]}]}];
        new PageUtil.MainPage({
            modulesConfig : config
        });
    });
</script>

</body>
</html>


