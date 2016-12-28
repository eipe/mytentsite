@if(Auth::check())
    <div class="row"><br /></div>
    <div class="row">
        <div class="large-8 large-centered columns text-center">
            <h4>Hi, {{ Auth::user()->name }}!</h4>
            <hr>
            @if(Auth::user()->socialLogin()->first())
            <p>You signed up through
            {{ ucfirst(trans(Auth::user()->socialLogin()->first()->provider)) }},<br>
            and the last authentication was {{ Auth::user()->socialLogin()->first()->updated_at }}.</p>
                <a href="{{ url('/auth/sign_out') }}" class="button">Sign out</a>
            @else

                <a class="button" href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

            @endif
        </div>
    </div>
    @if(Auth::user()->tentSites())
        <div class="row background-light-gray">
            <div class="large-8 large-centered columns text-center">
                <br>
                <h5><a data-toggle="user-contributions" title="Click to toggle view of contributions">
                        Your contributions ({{ Auth::user()->tentSites()->count() }})</a></h5>
                <br>
            </div>
            <div id="user-contributions" class="large-8 large-centered is-hidden" data-toggler=".is-hidden">
            @foreach(Auth::user()->tentSites()->getResults() as $tentSite)
                <div class="medium-4 column">
                    <img src="/storage/photos/tentsites/{{ $tentSite->img_location }}" />
                    <p>
                        <small>
                            {{ $tentSite->created_at }} |
                            <i class="fa fa-thumbs-up"></i> {{ $tentSite->likes }} |
                            @if($tentSite->approved)
                                <i class="fa fa-check"></i> Approved
                            @else
                                <i class="fa fa-circle"></i> Waiting for approval
                            @endif
                        </small>
                    </p>
                    <p><small>{{ $tentSite->caption }}</small></p>
                </div>
            @endforeach
                <br><br>
            </div>
        </div>
        <br><br>
    @endif
    @include('page.footer')
@else
    @include('auth.sign_up')
@endif