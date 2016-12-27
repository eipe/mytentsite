<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mytentsite - a collaborative tent collective</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#333333">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('/css/vendor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app_normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <nav class="top-bar">
        @if(Config::get('app.env') === 'staging')
            <div style="position: absolute; top: 10px; color: #e00;">
                &nbsp;&nbsp;<small><strong>BETA</strong></small>
            </div>
        @endif
        <ul id="menu" class="menu align-center" data-page-default="info">
            <li data-page="photo">
                <a href="/#/photo" title="Upload photo of your tent site"><i class="fa fa-camera"></i></a>
            </li>
            <li data-page="map">
                <a href="/#/map" title="Get a view of shared tent sites on the map">
                    <i class="fa fa-map-o"></i>
                </a>
            </li>
            <li data-page="wall">
                <a href="/#/wall" title="Explore shared tent sites by viewing our photo wall">
                    <i class="fa fa-th"></i>
                </a>
            </li>
            <li data-page="info">
                <a href="/#/info" title="Information about this service"><i class="fa fa-info"></i></a>
            </li>

            @if (Auth::check())
                <li data-page="user">
                    <a href="/#/user"><i class="fa fa-user"></i></a>
                </li>
            @else
                <li data-page="login">
                    <a href="/login" title="Log in"><i class="fa fa-user-o"></i></a>
                </li>
            @endif
        </ul>
    </nav>

    <div id="content">
        @section('content')
        @show
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
    @if(env('GOOGLE_ANALYTICS_TRACKING_ID'))
    <script>
        (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,"script","https://www.google-analytics.com/analytics.js","ga");

        ga("create", "{{ env('GOOGLE_ANALYTICS_TRACKING_ID') }}", "auto");
        ga("send", "pageview");
    </script>
    @endif
</body>
</html>
