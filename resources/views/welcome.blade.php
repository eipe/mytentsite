@extends('layouts.app')
@section('navigation')
    <nav class="top-bar">
        @if(Config::get('app.env') === 'staging')
        <div style="position: absolute; top: 10px; color: #e00;">
            &nbsp;&nbsp;<small><strong>BETA</strong></small>
        </div>
        @endif
        <ul id="menu" class="menu align-center" data-page-default="info">
            <li data-page="photo">
                <a href="#/photo" title="Upload photo of your tent site"><i class="fa fa-camera"></i></a>
            </li>
            <li data-page="map">
                <a href="#/map" title="Get a view of shared tent sites on the map">
                    <i class="fa fa-map-o"></i>
                </a>
            </li>
            <li data-page="wall">
                <a href="#/wall" title="Explore shared tent sites by viewing our photo wall">
                    <i class="fa fa-th"></i>
                </a>
            </li>
            <li data-page="info">
                <a href="#/info" title="Information about this service"><i class="fa fa-info"></i></a>
            </li>

            @if (Auth::check())
                <li data-page="user">
                    <a href="#/user"><i class="fa fa-user"></i></a>
                </li>
            @else
                <li data-page="login">
                    <a href="#/login" title="Log in"><i class="fa fa-sign-in"></i></a>
                </li>
                <li data-page="register" class="is-hidden">
                    <a href="#/register" title="Register"><i class="fa fa-plus-circle"></i></a>
                </li>
                <li data-page="password" class="is-hidden">
                    {{--<a href="#/password/reset" title="Reset password"><i class="fa fa-plus-circle"></i></a>--}}
                </li>
            @endif
        </ul>
    </nav>
@endsection

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
    <div class="page is-hidden page-allow-overflow" id="register">
        @include('auth.register')
    </div>
    <div class="page is-hidden page-allow-overflow" id="login">
        @include('auth.login')
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