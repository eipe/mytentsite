@extends('welcome')
@section('content')
    <div class="page page-allow-overflow">
        <div class="row">
            <div class="columns large-4 large-centered text-center">
                <h4>Reset password</h4>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="columns large-4 large-centered text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {{ csrf_field() }}
                    <label for="email" class="col-md-4 control-label">E-mail address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <button type="submit" class="button">Send password reset link</button>
                </form>
            </div>
        </div>
    </div>
@endsection
