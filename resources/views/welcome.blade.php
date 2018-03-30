@extends('layouts.app')

@section('header')
    <header>
        <nav class="navbar is-fixed-top is-light" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-brand">
                    <router-link to="/info" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="View information about this project">
                            mytentsite
                        </span>
                    </router-link>
                    <router-link to="/share" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Share a tent site">
                            <i class="fa fa-camera fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/locate" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Locate shared tent sites">
                            <i class="fa fa-map fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/explore" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Explore shared tent sites">
                            <i class="fa fa-th fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/user" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="User profile">
                            <i class="fa fa-user fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/admin" class="navbar-item has-text-centered" active-class="is-active" v-if="$auth.check('admin')">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Administrator">
                            <i class="fa fa-unlock-alt fa-lg"></i>
                        </span>
                    </router-link>
                </div>
            </div>
        </nav>
    </header>
@endsection

@section('content')
    <div id="content">
        <transition enter-active-class="animated fadeIn">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
    </div>
    <error></error>
@endsection