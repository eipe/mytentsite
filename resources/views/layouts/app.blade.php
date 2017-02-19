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
<div id="app">
    <nav class="top-bar">
        @if(Config::get('app.env') === 'staging')
            <div style="position: absolute; top: 10px; color: #e00;">
                &nbsp;&nbsp;<small><strong>BETA</strong></small>
            </div>
        @endif
        <ul id="menu" class="menu align-center">
            <li><router-link to="/share"><i class="fa fa-camera"></i></router-link></li>
            <li><router-link to="/map"><i class="fa fa-map-o"></i></router-link></li>
            <li><router-link to="/wall"><i class="fa fa-th"></i></router-link></li>
            <li><router-link to="/info"><i class="fa fa-info"></i></router-link></li>
            <li><router-link to="/user"><i class="fa fa-user-o"></i></router-link></li>
            <li><router-link to="/admin"><i class="fa fa-unlock-alt"></i></router-link></li>
        </ul>
    </nav>
    <div id="content">
        <keep-alive>
            <router-view :staging="{{ (Config::get('app.env') === 'staging') }}"></router-view>
        </keep-alive>
    </div>
    @if(Auth::check())
        <div class="is-hidden" id="api_token">{{Auth::user()->getAttribute('api_token')}}</div>
    @endif
    <photo-gallery></photo-gallery>
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
</div>
</body>
</html>
