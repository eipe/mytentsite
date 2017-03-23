@extends('layouts.app')

@section('navigation')
    <nav class="nav">
        @if(Config::get('app.env') === 'staging')
            <div class="is-pulled-left is-bold nav-item"><span style="color: #f00">BETA</span></div>
        @endif
        <ul class="nav-center">
            <li class="nav-item"><router-link to="/share"><i class="fa fa-camera"></i></router-link></li>
            <li class="nav-item"><router-link to="/map"><i class="fa fa-map-o"></i></router-link></li>
            <li class="nav-item"><router-link to="/wall"><i class="fa fa-th"></i></router-link></li>
            <li class="nav-item"><router-link to="/info"><i class="fa fa-info"></i></router-link></li>
            <li class="nav-item"><router-link to="/user"><i class="fa fa-user-o"></i></router-link></li>
            <li class="nav-item"><router-link to="/admin"><i class="fa fa-unlock-alt"></i></router-link></li>
        </ul>
    </nav>
@endsection

@section('content')
    <div id="content">
        <transition enter-active-class="animated fadeIn">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
    </div>
    <photo-gallery></photo-gallery>
@endsection