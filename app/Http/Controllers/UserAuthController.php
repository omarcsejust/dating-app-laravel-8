<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    /**
     * show login form if user is not logged in
     * or redirect to user dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
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

        $user = User::where('email', '=', $request->email)->first();
        if (isset($user)){
            //check Hash password match or not
            $is_matched = Hash::check($request->password, $user->password);
            if($is_matched){
                // set session
                $request->session()->put('user', $user->name);
                $request->session()->put('id', $user->id);

                // redirect to dashboard
                return redirect()->route('nearest-users');
            }else{
                return back()->with('status', 'Invalid password!');
            }
        }else{
            return back()->with('status', 'Invalid email!');
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
