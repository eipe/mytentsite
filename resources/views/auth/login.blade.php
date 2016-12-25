<div class="row"><br/></div>
<div class="row">
    <div class="columns large-centered text-center">
        <h4>Login</h4>

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <div class="row">
                <div class="medium-5 columns large-centered text-left">
                    <label for="email">E-Mail Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
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
                    <input type="checkbox" name="remember"> <label>Remember Me</label>

                    <a href="{{ url('/#/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="medium-5 columns large-centered text-right">
                    <button type="submit" class="button expanded">
                        Login
                    </button>
                    <a href="#/register" class="button" > Don't have an account?</a>
                    <a href="/auth/facebook" class="button">
                        <i class="fa fa-facebook"></i>&nbsp;&nbsp;Login with Facebook
                    </a>

                </div>
            </div>

        </form>
    </div>
</div>


<br><br>
@include('page.footer')