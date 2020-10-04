<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;

class UserAuthController extends Controller
{
    public function showLoginView(){
        //check user is logged in or not
        if (session()->has('id') && session()->has('user'))
            return redirect()->route('nearest-users');

        return view('auth/login');
    }

    public function login(Request $request){
        // validate user data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $result = User::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if (isset($result)){
            // set session
            $request->session()->put('user', $result->name);
            $request->session()->put('id', $result->id);

            // redirect to dashboard
            //return redirect()->route('nearest-users', ['id' => $result->id]);
            return redirect()->route('nearest-users');

        }else{
            return back()->with('status', 'Invalid email or password!');
        }
    }

    public function logout(){
        // reset session value
        if (session()->has('id') && session()->has('user')){
            session()->pull('id');
            session()->pull('user');
        }

        // finally redirect to login page
        return redirect()->route('login-view');
    }
}
