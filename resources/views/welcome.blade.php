<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>mytentsite - a collaborative tent collective</title>
        <link href="{{ asset('/css/vendor.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/css/app_normalize.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <nav class="top-bar">
            @if(Config::get('app.env') === 'staging')
            <div style="position: absolute; color: #e00;">
                &nbsp;&nbsp;<small><strong>BETA</strong></small>
            </div>
            @endif
            <ul id="menu" class="menu align-center" data-page-default="info">
                <li data-page="photo">
                    <a href="#/photo" title="Upload photo of your tent site"><i class="fa fa-camera"></i></a>
                </li>
                <li data-page="map">
                    <a href="#/map" title="Get a view of shared tent sites on the map">
                        <i class="fa fa-map-o"></i>
                    </a>
                </li>
                <li data-page="wall">
                    <a href="#/wall" title="Explore shared tent sites by viewing our photo wall">
                        <i class="fa fa-th"></i>
                    </a>
                </li>
                <li data-page="info">
                    <a href="#/info" title="Information about this service"><i class="fa fa-info"></i></a>
                </li>
                @if (Auth::check())
                    <li data-page="user">
                        <a href="#/user"><i class="fa fa-user"></i></a>
                    </li>
                @endif
            </ul>
        </nav>
        <div id="content">
            <div class="page is-hidden" id="photo">
                @include('page.photo')
            </div>
            <div class="page is-hidden" id="map"></div>
            <div class="page is-hidden page-allow-overflow" id="wall">
                @include('page.wall')
            </div>
            <div class="page is-hidden page-allow-overflow" id="info">
                @include('page.info')
            </div>
            @if(Auth::check())
                <div class="page is-hidden page-allow-overflow" id="user">
                    @include('page.user')
                </div>
            @endif
        </div>
        <div class="reveal" id="app-modal" data-reveal>
            <h4></h4>
            <p></p>
            <button class="close-button" data-close aria-label="Close" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <button data-close aria-label="Close" class="secondary button hollow">OK</button>
        </div>
        @if(Auth::check())
            <div class="is-hidden" id="api_token">{{Auth::user()->getAttribute('api_token')}}</div>
        @endif
        <script src="{{ asset('/js/vendor.js') }}"></script>
        <script src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
