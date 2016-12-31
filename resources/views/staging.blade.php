@extends('layouts.app')
@section('content')
    <div class="page page-allow-overflow">
        <div class="row">
            <div class="columns large-4 large-centered text-center" style="margin-top: 40px;">
                <img src="/images/mytentsite.svg" />
            </div>
        </div>
        <div class="row">
            <div class="columns large-4 large-centered text-left">
                <h4>Welcome to our beta version of mytent.site</h4>
                <p>We are currently in a closed beta period which means that only invited guests has
                    access to register. Just sign up to our newsletter from <a href="{{ url('/') }}">front page</a>
                    if you are interested and we will send you the code to access the beta version.</p>
                <p>All approved contributions
                    in the beta version will be kept and made public when we go live.</p>

                <form role="form" method="POST" action="{{ url('/beta') }}">
                            <label for="code">Code<small> - redeem yours by subscribing to our
                                    <a href="{{ url('/') }}">newsletter</a> </small></label>
                            <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus>
                            @if ($errors->has('code'))
                                <span class="round alert label">{{ $errors->first('code') }}</span>
                            @endif
                            <button type="submit" class="button expanded">Validate code</button>
                </form>
            </div>
        </div>
    </div>
    <div class="page page-allow-overflow">
        <div class="row"><br/></div>
        <div class="row">
            <div class="columns medium-centered text-center">
                <div class="row">
                    <div class="medium-6 columns medium-centered text-left">

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection