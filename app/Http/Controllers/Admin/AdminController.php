<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function check(Request $request){

        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required'
        ],[
            'email.exists' => 'This email is not Exist'
        ]);

        $confirm = $request->only('email','password');
        #if(Auth::attempt($confirm)){
        if(Auth::guard('admin')->attempt($confirm)){
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('admin.login')->with('fail','Incorect Credentials!');
        }

    }
    public function logout(){
        Auth::guard('admin')->logout();
       # Auth::logout();
        return redirect('/');
    }
}
