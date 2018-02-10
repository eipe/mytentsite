@extends('layouts.app')

@section('header')
    <header class="is-fixed-top">
        <nav class="nav">
            @if(Config::get('app.env') === 'staging')
                <div class="is-pulled-bottom is-bold nav-item"><span style="color: #f00">BETA</span></div>
            @endif
            <ul class="nav-center">
                <li class="nav-item">
                    <router-link to="/share" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Share a tent site">
                            <i class="fa fa-camera"></i>
                        </span>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/locate" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Locate shared tent sites">
                            <i class="fa fa-map-o"></i>
                        </span>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/explore" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Explore shared tent sites">
                            <i class="fa fa-th"></i>
                        </span>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/info" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="View information about this site">
                            <i class="fa fa-info"></i>
                        </span>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/user" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="User profile">
                            <i class="fa fa-user-o"></i>
                        </span>
                    </router-link>
                </li>
                <li class="nav-item" v-if="$auth.check('admin')">
                    <router-link to="/admin" active-class="is-active icon is-medium">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Administrator">
                            <i class="fa fa-unlock-alt"></i>
                        </span>
                    </router-link>
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