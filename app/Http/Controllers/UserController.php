<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gender;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    const MIN_DISTANCE = 50; // in KM
    public function getAllNearestUsers(){

        if (session()->has('id') && session()->has('user')){
            $user = User::where('id', session()->has('id'))->first();
            //return $user->location->lat;
            $query = "SELECT u.id, u.name, u.user_image, u.dob, g.gender, ROUND(6353 * 2 * ASIN(SQRT( POWER(SIN(({$user->location->lat} -
              abs(loc.lat)) * pi()/180 / 2),2) + COS(loc.lat * pi()/180 ) * COS(
              abs({$user->location->lat}) *  pi()/180) * POWER(SIN(({$user->location->lon} - loc.lon) *  pi()/180 / 2), 2) )), 2) as distance
              from locations loc 
              INNER JOIN users as u on loc.user_id = u.id
              INNER join genders g on u.gender_id = g.id 
              where user_id <>". session('id')."
              HAVING distance < ".self::MIN_DISTANCE;
            $nearest_users = DB::select($query);
            //return $nearest_users;
            return view('user/nearest_users_dashboard', ['users' => $nearest_users]);
        }else{
            return redirect()->route('login-view');
        }
    }

    public function registrationView(){
        $genders = Gender::all();
        return view('user/registration', ['genders' => $genders]);
    }

    public function addUser(Request $request){
        User::validateUser($request);

        // get geo info
        $geo_data = geoip()->getLocation($_SERVER['REMOTE_ADDR']);
        $lat = isset($geo_data['lat']) ? $geo_data['lat'] : null;
        $lon = isset($geo_data['lon']) ? $geo_data['lon'] : null;

        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'gender_id' => $request->gender_id,
            'dob' => $request->dob,
            'created_at' => Carbon::now()
        ];

        // redirect without storing data if geo info is missing
        if (empty($lat) || empty($lon))
            return back()->with('status', 'Location info not found!');

        $last_inserted_id = User::insertGetId($user);

        // add data to Location table
        $location = [
            'lat' => $lat,
            'lon' => $lon,
            'user_id' => $last_inserted_id,
            'created_at' => Carbon::now()
        ];

        Location::insert($location);

        return back()->with('status', 'Registration Success.');
    }
}
