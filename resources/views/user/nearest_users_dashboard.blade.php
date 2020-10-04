@extends('layouts.app')
@extends('layouts.navbar')

@section('title', 'Dating App')
@section('app-name', 'Date Easy')
@section('user-name', 'Hello '.session('user'))

@section('content')
    <div class="container">
        <div class="row">
            @forelse($users as $user)
                <div class="col-md-3 mt-2">
                    <div class="card shadow" style="width: 17rem;">
                        <img class="card-img-top rounded-circle p-1" src="{{asset('uploads/user_images')}}/{{ $user->user_image  }}" alt="Card image cap" width="200" height="200">
                        <div class="card-body">
                            <h5 class="card-title">{{$user->name}}</h5>
                            <label>{{$user->distance}} KM Away From You</label>
                            <div class="row">
                                <div class="col-md-4">{{$user->gender}}</div>
                                <div class="col-md-8">{{\Carbon\Carbon::parse($user->dob)->diff(\Carbon\Carbon::now())->format('%y Yr, %m M')}}</div>
                            </div>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            @empty
                <h2>User not found</h2>
            @endforelse
        </div>
    </div>
@endsection