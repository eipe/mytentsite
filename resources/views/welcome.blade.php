@extends('layouts.app')

<nav class="top-bar">
    @if(Config::get('app.env') === 'staging')
        <div style="position: absolute; top: 10px; color: #e00;">
            &nbsp;&nbsp;<small><strong>BETA</strong></small>
        </div>
    @endif
    <ul id="menu" class="menu align-center" data-page-default="info">
        <li data-page="photo">
            <a href="{{ url('/#/photo') }}" title="Upload photo of your tent site"><i class="fa fa-camera"></i></a>
        </li>
        <li data-page="map">
            <a href="{{ url('/#/map') }}" title="Get a view of shared tent sites on the map">
                <i class="fa fa-map-o"></i>
            </a>
        </li>
        <li data-page="wall">
            <a href="{{ url('/#/wall') }}" title="Explore shared tent sites by viewing our photo wall">
                <i class="fa fa-th"></i>
            </a>
        </li>
        <li data-page="info">
            <a href="{{ url('/#/info') }}" title="Information about this service"><i class="fa fa-info"></i></a>
        </li>

        @if (Auth::check())
            <li data-page="user">
                <a href="{{ url('/#/user') }}"><i class="fa fa-user"></i></a>
            </li>
        @else
            <li data-page="login">
                <a href="{{ url('/login') }}" title="Log in"><i class="fa fa-user-o"></i></a>
            </li>
        @endif
    </ul>
</nav>

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
    @if(Auth::check())
        <div class="page is-hidden page-allow-overflow" id="user">
            @include('page.user')
        </div>
    @endif
@endsection