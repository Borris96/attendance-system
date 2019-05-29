@extends('layouts.template')
@section('content')

<div class="login">
  <h1>欢迎来到 <br> JadeClass <br> 考勤管理系统</h1>
  <form method="GET" action="{{ route('login') }}">
    <button type="submit" class="btn btn-primary btn-block btn-large">点击进入登录页</button>
  </form>
</div>

@stop
