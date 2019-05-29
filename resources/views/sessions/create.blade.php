@extends('layouts.template')
@section('content')

<div class="login">
  <h1>JadeClass <br> 考勤管理系统</h1>
  @include('shared._warnings')
  <form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <input type="text" name="name" placeholder="用户名" required="required" value="{{ old('name') }}"/>
    <input type="password" name="password" placeholder="密码" required="required" value="{{ old('password') }}"/>
    <button type="submit" class="btn btn-primary btn-block btn-large">登录</button>
  </form>
</div>

@stop
