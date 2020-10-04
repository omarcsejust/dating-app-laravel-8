@extends('layouts.app')

@section('title', 'Dating App')

@section('content')
    @forelse($users as $user)
        {{$user->name}}
    @empty
        <h2>User not found</h2>
    @endforelse
@endsection