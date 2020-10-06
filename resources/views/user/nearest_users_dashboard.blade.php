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
                                <div class="col-md-8">{{\Carbon\Carbon::parse($user->dob)->diff(\Carbon\Carbon::now())->format('%y Yrs old')}}</div>
                            </div>

                            {{--button like dislike and color login here--}}
                            @if($like_dislikes->count() == 0)
                                <a href="javascript:void(0)" onclick="likeProfile({{$user->id}}, null, null)" class="btn btn-primary btn-block mt-1">Like</a>
                            @endif

                            @foreach($like_dislikes as $key=>$like_dislike)
                                @if($like_dislike->profile_id === $user->id && $like_dislike->like_dislike_status === 1)
                                    <a href="javascript:void(0)" onclick="likeProfile({{$user->id}}, {{$like_dislike->id}}, true)" class="btn btn-danger btn-block mt-1">Dislike</a>
                                    @break
                                @elseif($like_dislike->profile_id === $user->id && $like_dislike->like_dislike_status === 0)
                                    <a href="javascript:void(0)" onclick="likeProfile({{$user->id}}, {{$like_dislike->id}}, false)" class="btn btn-primary btn-block mt-1">Like</a>
                                    @break
                                @elseif($key == $like_dislikes->count()-1) {{--the above condition does not meet so show this now--}}
                                    <a href="javascript:void(0)" onclick="likeProfile({{$user->id}}, null, null)" class="btn btn-primary btn-block mt-1">Like</a>
                                @endif
                            @endforeach
                            {{--<a href="javascript:void(0)" onclick="likeProfile({{$user->id}})" class="btn btn-primary btn-block mt-1">Like</a>--}}
                        </div>
                    </div>
                </div>
            @empty
                <h2 class="text-center mt-4">User not found around you!</h2>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="matchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Match Found</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    It's a Match!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-section')
    <script type="text/javascript">

        function likeProfile(profile_id, like_dislike_id, like_status){
            let CSRF_TOKEN = '{{ csrf_token() }}';

            $.ajax({
                type: "POST",
                url: "{{ url('like/user') }}",
                dataType: 'json',
                data: {
                    profile_id: profile_id,
                    like_dislike_id: like_dislike_id,
                    like_status: like_status,
                    _token: CSRF_TOKEN
                },
                success: function(response) {
                    console.log(response);
                    //here you can get response sent from server end
                    if (response.is_mutual){
                        $('#matchModal').modal('show');
                    }else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                },
                complete: function(){
                    //do something on complete
                }
            });

        }

        $('.close-modal').on('click', function (e) {
            location.reload();
        });
    </script>
@endsection