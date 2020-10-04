@extends('layouts/app')

@section('title', 'Sign up')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-4">
                <div class="card shadow">
                    <div class="card-header">Registration</div>
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

                        <form action="{{url('user/registration')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{old('name')}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{old('email')}}">
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{old('dob')}}">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>

                            {{--show gender list--}}
                            <label for="gender">Gender</label>
                            <div class="form-group">
                                @forelse($genders as $gender)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender_id" id="inlineRadio1" value="{{$gender->id}}">
                                        <label class="form-check-label" for="inlineRadio1">{{$gender->gender}}</label>
                                    </div>
                                @empty
                                    <label>Genders not found</label>
                                @endforelse
                            </div>

                            <div class="form-group">
                                <label for="user_image">Upload a Photo</label>
                                <input type="file" class="form-control" name="user_image">
                            </div>

                            <button type="submit" class="btn btn-block btn-primary">Sign up</button>

                            <label class="mt-2">Already have an account? <a href="{{url('user/login/view')}}">Sign in</a></label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script type="text/javascript">
    //
</script>