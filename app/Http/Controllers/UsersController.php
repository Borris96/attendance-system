<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['create', 'store']
        ]);
    }

    // public function show(){
    //     return view('static_pages.index');
    // }

    public function show(User $user)
    {
        $this->authorize('show', $user);
        return view('static_pages/index');
        return route('home');
    }
}
