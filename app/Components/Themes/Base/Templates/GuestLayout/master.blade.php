{{--@include('GuestLayout.header')
<div class="clearfix"></div>
<div class="page-container container">
    <div class="page-content-wrapper">
        <div class="page-content">
            @section('pageTitle')
            <h1 class="page-title"> Home page
                <small>Hybrid MLM </small>
            </h1>
            @show
            @yield('content')
        </div>
    </div>
</div>
@include('GuestLayout.footer')--}}



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="@yield('metaDescription')" name="description"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif</title>
    <link rel="shortcut icon" href="{{ asset(getConfig('company_information', 'favicon')) }}"/>

    <link href="{{ asset('global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/user/css/meanmenu.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('css/user/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/all_5.10.20.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
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

    @if(isset($styles) && $styles)
        @foreach($styles as $style)
            <link href="{{ $style }}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif
    <style type="text/css">
        
        .mainBannerWrapper {
            position: relative;
        }

        .headerSection {
            position: fixed;
            top: 0;
            z-index: 2;
            width: 100%;
            transition: 0.3s;
            display: none;
        }

        .navbar-toggle {
            margin-right: 0;
        }

        .headerSection a.navbar-brand img {
            width: 210px;
        }

        .headerSection a.navbar-brand {
            padding: 0;
        }

        .headerSection nav.navbar {
            background-color: transparent;
            border: 0;
            border-radius: 0;
            margin-bottom: 0;
            padding: 0 0 !important;
        }

        .headerSection nav.navbar .container-fluid.border-bottom {
            /*border-bottom: solid 2px #a0a0a096;*/
            padding: 0 50px;
        }

        #navbarNav {
            height: 100%;
        }

        @media (max-width: 992px) {
            .navbar ul.nav.navbar-nav li a {
                font-size: 1.2vw;
            }
        }

        .navbar ul.nav.navbar-nav {
            /*margin-top: 18px;*/
            height: 100%;
        }

        .navbar ul.nav.navbar-nav li a {
            display: block;
            color: #3e3e3e;
            padding: 26px 18px;
            font-weight: 400;
            font-size: 15px;
            font-family: "Oswald", Open Sans, Helvetica, sans-serif;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            #navbarNav {
                display: none !important;
            }
            .meanmenu-div {
                display: block !important;
            }
        }

        @media (min-width: 992px) {
            .meanmenu-div {
                display: none !important;
            }
        }

        .navbar ul.nav.navbar-nav li a:hover {
            background-color: #f8f8f8 !important;
            color: #d22630;
        }

        .navbar ul.nav.navbar-nav li.active a {
            background-color: #f8f8f8 !important;
            color: #00aea9;
        }

        .navbar ul.nav.navbar-nav a span {
            display: block;
            font-size: 15px;
            color: #989797;
            font-family: "Oswald", Open Sans, Helvetica, sans-serif;
        }

        .navbar ul.nav.navbar-nav li {
            /*border-left: solid 1px #eee;*/
            /* border-bottom: solid 3px #ff000000; */
            position: relative;
            align-self: center;
        }

        .navbar ul.nav.navbar-nav li:hover:after {
            /*content: '';*/
            background: #08b790;
            /*bottom: 0;
            width: 100%;
            position: absolute;
            height: 4px;*/
        }

        .navbar ul.nav.navbar-nav li a span:hover {
            color: #08b790;
        }

        .navbar ul.nav.navbar-nav li:hover a span {
            color: #08b790;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99;
        }

        .headerSection.fixed-header nav.navbar {
            background-color: #fff;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .headerSection.fixed-header {
            background-color: #fff;
            margin-bottom: 0;
            box-shadow: #5d5959 4px 3px 16px;
        }
    </style>
@yield('PAGE_LEVEL_STYLES')

</head>
<body>

@yield('PAGE_START')


<div class="mainBannerWrapper">
    <div class="headerSection fixed-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container" style="background-color: #fff; height: 75px; display: flex; align-items: center;">
                <a class="navbar-brand" href="{{scopeRoute('home')}}"><img src="{{ asset('images/ElysiumLogo.png') }}" class="img-fluid" alt="{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif" title="{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif" /></a>
                <div class="collapse navbar-collapse" id="navbarNav" style="margin-left: auto;">
                    <ul class="nav navbar-nav">
                        <li class="nav-item" id="home-nav">
                            <a href="https://www.elysiumnetwork.io" class="scroll">HOME</a>
                        </li>
                        <li class="nav-item" id="about-nav">
                            <a href="https://www.elysiumnetwork.io" class="scroll">ABOUT</a>
                        </li>
                        <li class="nav-item" id="portfolios-nav">
                            <a href="https://www.elysiumnetwork.io" class="scroll">PRODUCTS</a>
                        </li>
                        <li class="nav-item" id="team-nav">
                            <a href="https://www.elysiumnetwork.io" class="scroll">TEAM</a>
                        </li>
                        <li class="nav-item contact-nav">
                            <a href="https://www.elysiumnetwork.io/contact-us" class="scroll link">CONTACT</a>
                        </li>
                        <li class="nav-item contact-nav">
                            <a href="{{ route('user.login') }}">LOGIN</a>
                        </li>
                        <li class="nav-item contact-nav">
                            <a href="{{ route('user.register') }}">JOIN</a>
                        </li>
                    </ul>
                </div>
                <div class="meanmenu-div">
                    <nav class="menamenu-nav">
                        <ul>
                            <li >
                                <a href="https://www.elysiumnetwork.io" class="scroll">HOME</a>
                            </li>
                            <li >
                                <a href="https://www.elysiumnetwork.io" class="scroll">ABOUT</a>
                            </li>
                            <li>
                                <a href="https://www.elysiumnetwork.io" class="scroll">PRODUCTS</a>
                            </li>
                            <li>
                                <a href="https://www.elysiumnetwork.io" class="scroll">TEAM</a>
                            </li>
                            <li>
                                <a href="https://www.elysiumnetwork.io/contact-us" class="scroll link">CONTACT</a>
                            </li>
                            <li>
                                <a href="{{ route('user.login') }}">LOGIN</a>
                            </li>
                            <li>
                                <a href="{{ route('user.register') }}">JOIN</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </nav>
    </div>
</div>
@yield('content')



<script src="{{ asset('global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('global/plugins/jquery.cookie.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('global/plugins/ladda/spin.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('global/plugins/ladda/ladda.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/User/js/jquery.meanmenu.js')}}"></script>
<script src="{{ asset('global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
@if(isset($scripts) && $scripts)
    @foreach($scripts as $script)
        <script src="{{ $script }}" type="text/javascript"></script>
    @endforeach
@endif
@yield('PAGE_LEVEL_SCRIPTS')


@yield('PAGE_END')
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.meanmenu-div .menamenu-nav').meanmenu({
            meanScreenWidth: '992'
        });
    });
</script>
<script type="text/javascript">        
    $('.headerSection').show();
</script>
</body>
</html>
