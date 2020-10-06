<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gender;
use App\Models\Location;
use App\Models\LikeDislike;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;

class UserController extends Controller
{
    const MIN_DISTANCE = 6; // in KM

    /**
     * find nearest users and like-dislike result for logged user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getAllNearestUsers(){
        if (session()->has('id') && session()->has('user')){

            $user = User::where('id', session('id'))->first();

            // get liked or disliked users by logged in user
            $like_dislike_data = LikeDislike::where('user_id',session('id'))->get();

            // build query to find nearest users
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
            return view('user/nearest_users_dashboard', ['users' => $nearest_users, 'like_dislikes' => $like_dislike_data]);
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

        // get geo info: Not working on free hosting on Heroku
        /*$geo_data = geoip()->getLocation($_SERVER['REMOTE_ADDR']);
        $lat = isset($geo_data['lat']) ? $geo_data['lat'] : null;
        $lon = isset($geo_data['lon']) ? $geo_data['lon'] : null;*/

        $lat = $request->lat;
        $lon = $request->lon;

        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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

        // add image to user table
        if ($request->hasFile('user_image')){ //if file submitted through this form with name product_image
            $image_to_upload = $request->user_image;
            $file_name = $last_inserted_id.'.'.$image_to_upload->getClientOriginalExtension();
            Image::make($image_to_upload)->resize(400,450)->save(base_path('public/uploads/user_images/'.$file_name));
            User::findOrFail($last_inserted_id)->update([
                'user_image'=>$file_name
            ]);
        }

        //return back()->with('status', 'Registration Success.');  // for form submission
        return json_encode(['status' => 200,'message' => 'Registration Success.']);

    }
}
