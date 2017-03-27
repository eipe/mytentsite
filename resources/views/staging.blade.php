@extends('layouts.app')
@section('content')
    <section class="hero is-fullheight is-info">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-half is-offset-one-quarter has-text-centered">
                        <img src="/images/192-circle.png" />
                        <h1 class="title">Welcome to our beta version</h1>
                        <h2 class="subtitle">Enter the provided code and you are good to go!</h2>
                        <form role="form" method="POST" action="{{ url('/beta') }}">
                            <input id="code" type="text" name="code" placeholder="Code" value="{{ old('code') }}"
                                   class="input" required autofocus>
                            @if ($errors->has('code'))
                                <span class="is-danger">{{ $errors->first('code') }}</span>
                            @endif
                            <br><br>
                            <button type="submit" class="button">Validate code</button>
                        </form><br>
                        <p class="help">If you got here directly, we must kindly ask you to go to
                            <u><a href="{{ url('/') }}">the front page</a></u> and follow the instructions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection