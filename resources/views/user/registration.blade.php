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

                        <span id="ajax-error-msg"></span>

                        <form action="" method="" enctype="multipart/form-data">
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

                            <button type="submit" id="submit-btn" class="btn btn-block btn-primary">Sign up</button>

                            <label class="mt-2">Already have an account? <a href="{{url('user/login/view')}}">Sign in</a></label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-section')
    <script type="text/javascript">

        $('#submit-btn').on('click', (e) => {
            e.preventDefault();
            let CSRF_TOKEN = '{{ csrf_token() }}';

            // get geo location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(redirectToPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
                return;
            }

            // callback function
            function redirectToPosition(position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;
                //alert(lat);
                //return;
                $.ajax({
                    type: "POST",
                    url: "{{ url('user/registration') }}",
                    dataType: 'json',
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        dob: $('#dob').val(),
                        password: $('#password').val(),
                        lat: lat,
                        lon: lon,
                        gender_id: $('input[name="gender_id"]:checked').val(),
                        _token: CSRF_TOKEN,
                    },
                    success: function(response) {
                        //here you can get response sent from server end
                        console.log(response);
                        if (response.status == 200){
                            document.getElementById('ajax-error-msg').innerHTML = "<h3 style='color:green'>" + response.message + "</h3>";
                        }
                    },
                    error: function(xhr) {
                        //console.log(xhr);
                        let response = xhr.responseText;
                        let parsed_res = JSON.parse(response);
                        let error_list = "";
                        for (let key in parsed_res.errors){
                            error_list += "<li style='color:red'>" +  parsed_res.errors[key] + "</li>";
                        }
                        document.getElementById('ajax-error-msg').innerHTML = "<ul>"+error_list+"</ul>";
                    },
                    complete: function(){
                        //do something on complete
                    }
                });
            }

        });
    </script>
@endsection