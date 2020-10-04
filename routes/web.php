<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('login');
    return redirect('user/login/view');
});

//Route::view('/user/login','login');
Route::get('/user/login/view',[UserAuthController::class,'showLoginView'])->name('login-view');
Route::post('/user/login',[UserAuthController::class,'login']);
Route::get('/user/logout',[UserAuthController::class,'logout']);

Route::get('/user/registration/view', [UserController::class, 'registrationView']);
Route::get('/nearest/users', [UserController::class, 'getAllNearestUsers'])->name('nearest-users');
Route::post('/user/registration', [UserController::class, 'addUser']);
