@extends('layouts.app')
@section('content')
    <div class="page is-hidden" id="photo">
        @include('page.photo')
    </div>
    <div class="page is-hidden" id="map"></div>
    <div class="page is-hidden page-allow-overflow" id="wall">
        @include('page.wall')
    </div>
    <div class="page is-hidden page-allow-overflow" id="info">
        @include('page.info')
    </div>
    <div class="page is-hidden page-allow-overflow" id="password">
        {{--@include('auth.passwords.reset')--}}
    </div>
    @if(Auth::check())
        <div class="page is-hidden page-allow-overflow" id="user">
            @include('page.user')
        </div>
    @endif
@endsection