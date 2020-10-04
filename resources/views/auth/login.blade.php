@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center" style="height: 350px">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        Sign In
                    </div>
                    <div class="card-body">

                        {{-- show status --}}
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- show all validation errors here --}}
                        @if($errors->all())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error  }}</li>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{url('user/login')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-block btn-primary">Sign In</button>
                            <label class="mt-2">New User? <a href="{{url('user/registration/view')}}">Sign up now</a></label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection