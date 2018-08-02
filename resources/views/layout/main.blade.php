<!DOCTYPE html>
<html>
    <head>
        <title>Arbory</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="{{ mix('/css/application.css', 'arbory') }}" media="all" rel="stylesheet"/>
        <link href="{{ asset('/arbory/vendor/core_ui/css/bootstrap.css') }}" media="all" rel="stylesheet"/>
        <link href="{{ asset('/arbory/vendor/core_ui/css/coreui.css') }}" media="all" rel="stylesheet"/>
        <link href="{{ mix('/css/controllers/nodes.css', 'arbory') }}" media="all" rel="stylesheet"/>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @foreach($assets->getCss() as $css)
            <link href="{{ $css }}" media="all" rel="stylesheet"/>
        @endforeach

        @foreach($assets->getInlineCss() as $style)
            <style>
                {!! $style !!}
            </style>
        @endforeach
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show {{ $body_class ?? '' }}">

        @include('arbory::layout.partials.header')

        <div class="app-body">
            @include('arbory::layout.partials.menu')
            <main class="main">
                @yield('content.header')
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('arbory.services.google.maps_api_key') }}&libraries=places"></script>

        <script src="{{ mix('/js/dependencies.js', 'arbory') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="{{ mix('/js/application.js', 'arbory') }}"></script>
        <script src="{{ mix('/js/controllers/nodes.js', 'arbory') }}"></script>

        @foreach($assets->getJs() as $script)
            <script src="{{ asset($script, 'arbory') }}"></script>
        @endforeach

        @foreach($assets->getInlineJs() as $inlineJs)
            <script type="text/javascript">
                {!! $inlineJs !!}
            </script>
        @endforeach
    </body>
</html>
