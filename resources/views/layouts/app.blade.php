<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mytentsite - a collaborative tent collective</title>
    <link rel="shortcut icon" type="image/png" href="/images/mytentsite-32x32.png" sizes="32x32">
    <link rel="shortcut icon" type="image/png" href="/images/mytentsite-16x16.png" sizes="16x16">
    <link rel="shortcut icon" href="/images/mytentsite.ico">
    <link href="{{ asset('/css/vendor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app_normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    @section('navigation')
    @show

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
