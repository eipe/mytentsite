<!DOCTYPE html>
<html>
<head>
    <title>mytentsite - a collaborative tent collective</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/180-circle.png">
    <link rel="icon" type="image/png" href="/images/16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/images/32x32.png" sizes="32x32">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#333333">
    <link href="{{ asset('/css/core-dep.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/map-dep.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app_normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="app" class="has-fixed-header">
        <header>
            @yield('navigation')
        </header>
        @yield('content')
    </div>
    @if(Auth::check())
        <div class="is-hidden" id="api_token">{{Auth::user()->getAttribute('api_token')}}</div>
    @endif
    <div class="is-hidden" id="environment">{{ Config::get('app.env') }}</div>
    <script src="{{ asset('/js/core-dep.js') }}"></script>
    <script src="{{ asset('/js/map-dep.js') }}"></script>
    <script src="{{ asset('/js/share-dep.js') }}"></script>
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
