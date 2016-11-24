@if(Auth::check())
        <div class="row"><br /></div>
        <div class="row">
            <div class="medium-6 large-centered columns">
                You are logged in as {{ Auth::user()->name }},
                authenticated through
                {{ ucfirst(trans(Auth::user()->socialLogin()->first()->provider)) }}.<br />
                Last authentication was {{ Auth::user()->socialLogin()->first()->updated_at }}.<br />
            </div>
        </div>
        <div class="row">
            <div class="medium-6 large-centered columns">
                <a href="#" class="button">Logout</a>
            </div>
        </div>
    </div>
@else
    @include('auth.sign_in');
@endif