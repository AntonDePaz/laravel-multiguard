<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required',
            'email'=> 'required|unique:users,email',
            'password' => 'required|min:5|max:30',
            'password_confirmation' => 'required|min:5|max:30|same:password'
        ]);

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = \Hash::make($request->password);
        $users->save();

        if($users){
            return redirect()->back()->with('success','Succesfully Added User!');
        }else{
            return redirect()->back()->with('fail','Something went wrong');
        }

    }

    public function check(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:5|max:30'
        ],[
            'email.exists'=>'This email is not exist on users table'
        ]);

        $confirm = $request->only('email','password');
        #if(Auth::attempt($confirm)){
        if(Auth::guard('web')->attempt($confirm)){
            return redirect()->route('user.home');
        }else{
            return redirect()->route('user.login')->with('fail','Incorect Credentials!');
        }

    }
    public function logout(){
        Auth::guard('web')->logout();
       # Auth::logout();
        return redirect('/');
    }
}
