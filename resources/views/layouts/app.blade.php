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
    @section('navigation')
    @show
    <div id="content">
        @section('content')
        @show
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
