@extends('layouts.app')

@section('navigation')
<nav class="top-bar">
    @if(Config::get('app.env') === 'staging')
        <div style="position: absolute; top: 10px; color: #e00;">
            &nbsp;&nbsp;<small><strong>BETA</strong></small>
        </div>
    @endif
    <ul id="menu" class="menu align-center">
        <li><router-link to="/share"><i class="fa fa-camera"></i></router-link></li>
        <li><router-link to="/map"><i class="fa fa-map-o"></i></router-link></li>
        <li><router-link to="/wall"><i class="fa fa-th"></i></router-link></li>
        <li><router-link to="/info"><i class="fa fa-info"></i></router-link></li>
        <li><router-link to="/user"><i class="fa fa-user-o"></i></router-link></li>
        <li><a href="{{ url('/admin/#admin') }}"><i class="fa fa-unlock-alt"></i></a></li>
    </ul>
</nav>
@endsection

@section('content')
    <keep-alive>
            <router-view :staging="{{ (Config::get('app.env') === 'staging') }}"></router-view>
    </keep-alive>
@endsection