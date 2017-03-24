@extends('layouts.app')
@section('content')
    <section class="hero is-fullheight is-info">
        <div class="container">
            <div class="columns">
                <div class="column is-half is-offset-one-quarter has-text-centered">
                    <img src="/images/192-circle.png" />
                    <h1 class="title">Welcome to our beta version of mytent.site</h1>
                    <p>We are currently in a closed beta period which means that only invited guests has
                    access to register. Just sign up to our newsletter from <a href="{{ url('/') }}">front page</a>
                    if you are interested and we will send you the code to access the beta version.</p>
                    <p>All approved contributions
                    in the beta version will be kept and made public when we go live.</p>

                    <form role="form" method="POST" action="{{ url('/beta') }}">
                            <label for="code">Code<small> - redeem yours by subscribing to our
                                    <a href="{{ url('/') }}">newsletter</a> </small></label>
                            <input id="code" type="text" name="code" value="{{ old('code') }}"
                                   class="input" required autofocus>
                            @if ($errors->has('code'))
                                <span class="is-danger">{{ $errors->first('code') }}</span>
                            @endif
                            <button type="submit" class="button expanded">Validate code</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection