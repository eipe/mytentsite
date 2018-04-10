@extends('layouts.app')

@section('header')
    <header-element></header-element>
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