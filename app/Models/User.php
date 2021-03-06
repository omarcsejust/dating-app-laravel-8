<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_image',
    ];

    //validate customer
    public static function validateUser(Request $request){
        return $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
            'gender_id' => 'required|integer',
            'dob' => 'required',
            'user_image' => 'image|max:200', // Validate that an uploaded file is exactly 512 kilobytes...
        ]);
    }

    /**
     * Get the location record associated with the user.
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    // mutator to hash user password
    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
