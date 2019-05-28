<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'name'=>'required',
            'password'=>'required|max:60'
        ]);
        if (Auth::attempt($credentials)){
            session()->flash('success','登录成功！');
            return redirect()->route('home',[Auth::user()]);
        } else {
            session()->flash('danger','密码或用户名错误。');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('sucess','您已成功退出！');
        return redirect('login');
    }
}
