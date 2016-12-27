@extends('layouts.app')
@section('content')
<div class="page page-allow-overflow">
    <div class="row"><br/></div>
    <div class="row">
        <div class="columns large-centered text-center">
            <h4>Create a new account</h4>
            <form role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}

                <div class="row">
                    <div class="medium-5 columns large-centered text-left">
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="round alert label">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="medium-5 columns large-centered text-left">
                        <label for="email">E-Mail Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="round alert label">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="medium-5 columns large-centered text-left">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required>

                        @if ($errors->has('password'))
                            <span class="round alert label">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="medium-5 columns large-centered text-left">
                        <label for="password-confirm">Confirm Password</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required>
                    </div>
                </div>

                <div class="row">
                    <div class="medium-5 columns large-centered text-right">
                        <button type="submit" class="button expanded">
                            Create a new account
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="medium-5 columns large-centered text-right">
                        <a href="/auth/facebook" class="button">
                            <i class="fa fa-facebook"></i>&nbsp;&nbsp;Sign up with Facebook
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    @include('page.footer')
</div>
@endsection