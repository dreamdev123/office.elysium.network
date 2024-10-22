<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif</title>

    <!-- Bootstrap -->

    <link href="{{ asset('css/user/css/bootstrap_4.1.3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/meanmenu.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('css/user/css/dashboard_fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/all_5.10.20.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/style.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="{{ asset('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('js/User/js/jquery.meanmenu.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- BEGIN PAGE LEVEL TOP PLUGINS -->
    @if(isset($headScripts) && $headScripts)
        @foreach($headScripts as $script)
            <script src="{{ $script }}" type="text/javascript"></script>
        @endforeach
    @endif

    @if(isset($jsVariables) && $jsVariables)
        <script type="text/javascript">
            @foreach($jsVariables as $key => $variable)
                var {{ $key }} = '{{ $variable }}';
            @endforeach
        </script>
    @endif

<!-- END PAGE LEVEL TOP PLUGINS -->

    <!-- BEGIN PAGE LEVEL STYLES -->
@yield('PAGE_LEVEL_STYLES')
<!-- END PAGE LEVEL STYLES -->
</head>
<body>
<!-- BEGIN PAGE START SECTION -->
@yield('PAGE_START')
<!-- END PAGE START SECTION -->

@yield('content')


<!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="{{ asset('js/User/js/bootstrap_4.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/User/js/custom.js') }}"></script>
    <!-- <script src="{{ asset('global/plugins/ladda/spin.min.js') }}" type="text/javascript"></script> -->
    <script src="{{ asset('global/plugins/ladda/ladda.min.js') }}" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
@yield('PAGE_LEVEL_SCRIPTS')
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN PAGE END SECTION -->
@yield('PAGE_END')
<!-- END PAGE END SECTION -->
</body>
</html>
