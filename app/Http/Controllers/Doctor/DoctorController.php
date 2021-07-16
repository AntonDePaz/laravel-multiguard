<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required',
            'hospital' => 'required',
            'email'=> 'required|unique:doctors,email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password'
        ]);

        $doctors = new Doctor();
        $doctors->name = $request->name;
        $doctors->hospital = $request->hospital;
        $doctors->email = $request->email;
        $doctors->password = \Hash::make($request->password);
        $doctors->save();

        if($doctors){
            return redirect()->back()->with('success','Succesfully Added Doctor!');
        }else{
            return redirect()->back()->with('fail','Something went wrong');
        }


   }

   public function check(Request $request){

    $request->validate([
        'email' => 'required|email|exists:doctors,email',
        'password' => 'required|min:5|max:30'
    ],[
        'email.exists'=>'This email is not exist on Doctors table'
    ]);

    $confirm = $request->only('email','password');
    #if(Auth::attempt($confirm)){
    if(Auth::guard('doctor')->attempt($confirm)){
        return redirect()->route('doctor.home');
    }else{
        return redirect()->route('doctor.login')->with('fail','Incorect Credentials!');
    }

}
public function logout(){
    Auth::guard('doctor')->logout();
   # Auth::logout();
    return redirect('/');
}

}
