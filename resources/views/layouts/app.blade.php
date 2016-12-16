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
</body>
</html>
