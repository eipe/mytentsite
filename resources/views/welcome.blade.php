@extends('layouts.app')

@section('header')
    <header class="is-fixed-top">
        <nav class="nav">
            @if(Config::get('app.env') === 'staging')
                <div class="is-pulled-left is-bold nav-item"><span style="color: #f00">BETA</span></div>
            @endif
            <ul class="nav-center">
                <li class="nav-item"><router-link to="/share"><i class="fa fa-camera"></i></router-link></li>
                <li class="nav-item"><router-link to="/locate"><i class="fa fa-map-o"></i></router-link></li>
                <li class="nav-item"><router-link to="/wall"><i class="fa fa-th"></i></router-link></li>
                <li class="nav-item"><router-link to="/info"><i class="fa fa-info"></i></router-link></li>
                <li class="nav-item"><router-link to="/user"><i class="fa fa-user-o"></i></router-link></li>
                <li class="nav-item" v-if="$auth.check('admin')">
                    <router-link to="/admin"><i class="fa fa-unlock-alt"></i></router-link>
                </li>
            </ul>
        </nav>
    </header>
@endsection

@section('content')
    <div id="content" class="has-fixed-header-top">
        <transition enter-active-class="animated fadeIn">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
    </div>
    <photo-gallery></photo-gallery>
    <error></error>
@endsection