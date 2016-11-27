@if(Auth::check())
        <div class="row"><br /></div>
        <div class="row">
            <div class="medium-6 large-centered columns text-center">
                <h4>Hi, {{ Auth::user()->name }}!</h4>
                <hr>
                @if(Auth::user()->socialLogin()->first())
                <p>You signed up through
                {{ ucfirst(trans(Auth::user()->socialLogin()->first()->provider)) }},<br>
                and the last authentication was {{ Auth::user()->socialLogin()->first()->updated_at }}.</p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="medium-6 large-centered columns text-center">
                <a href="/auth/sign_out" class="button">Sign out</a>
            </div>
        </div>
    </div>
@else
    @include('auth.sign_up');
@endif