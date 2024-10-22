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
    <link href="{{ asset('css/user/css/dashboard_fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/style.css') }}" rel="stylesheet">
    <style type="text/css">

        .headerSection nav.navbar.navbar-default {
            border-bottom: solid 2px #a0a0a000 !important;
        }
        .bg-login-image {
            background-color: #dcddde;
            z-index: 2; 
            min-height: calc(100vh - 93px);
        }
        .register-title {
            font-family: 'DIN Pro Condensed Bold', sans-serif;
            font-size: 35px; 
            color: #d22630;
        }
        .register-subtitle {
            font-family: 'DIN Pro Condensed Bold', sans-serif; 
            font-size: 12px; 
            color: #41464d;
        }
        .register-desc {
            font-family: 'Raleway Light', sans-serif;
            font-size: 12px; 
            color: #41464d;
        }
        .form-right-padding {
            padding-right: 40px;
        }

        .form-left-padding {
            padding-left: 40px;
        }

        @media (max-width: 992px) {
            .form-right-padding {
                padding-right: 15px;
            }

            .form-left-padding {
                padding-left: 15px;
            }
        }

        .info-title {
            font-family: 'DIN Pro Condensed Medium', sans-serif; 
            font-size: 21px; 
            color: #41464d;
            text-transform: uppercase;
        }

        .info-subtitle {
            font-family: 'DIN Pro Condensed Regular', sans-serif; 
            font-size: 16px; 
            color: #41464d;
            text-transform: uppercase;
            font-weight: 400;
        }
        .label-style {
            font-family: 'DIN Pro Condensed Regular';
            font-size: 16px;
            color: #41464d;
        }
        .input-form {
            width: 100%;
            height: 33px;
            font-family: 'Raleway Regular', sans-serif;
            font-size: 12px;
            color: #a6a8ab;
        }
        label.has-error {
            padding: 13px 16px 11px;
            font-size: 14px;
            color: #D22630;
            background: #ffecec;
            border: 1px solid #D22630;
            border-top: none;
            margin: 0;
            border-radius: 0 0 .25rem .25rem;
            width: 100%;
            text-align: left;
            font-family: 'calibri';
        }
        label.valid {
            padding: 13px 16px 11px;
            font-size: 14px;
            color: #5ea06d;
            background: #e4f7e5;
            border: 1px solid #5ea06d;
            border-top: none;
            margin: 0;
            border-radius: 0 0 .25rem .25rem;
            width: 100%;
            text-align: left;
            font-family: 'calibri';
        }
        .button-submit {
            width: 100%;
        }
        .footer-disclaimer {
            padding-top: 80px; 
            padding-bottom: 80px;
        }

        .footer-disclaimer .disclaimer-title {
            font-family: 'DIN Pro Condensed Bold', sans-serif;
            font-size: 10pt;
            color: #41464d;
            text-align: justify;
        }


        .footer-disclaimer .disclaimer-desc {
            font-family: 'Raleway Light', sans-serif;
            font-size: 10pt;
            color: #41464d;
            text-align: justify;
        }

        @media (max-width: 1350px) {
            .footer-disclaimer p {
                padding-left: 30px;
                padding-right: 30px;
            }
        }
        .webkit_style {
            background: url("../images/select-arrows.svg") no-repeat;
            background-position: 98% 50%;
            background-size: 12px;
        }
        .select-bg {
            background: #fff;
            width: 100%;
            border-radius: 0.25rem;
        }
        .select-bg select {
            width: 100%;
            -webkit-appearance: menulist-button;
        }
        .payment-choose-title {
            font-family: 'DIN Pro Condensed Bold', sans-serif;
            font-size: 35px;
            color: #d22630;
        }
        .payment-btn {
            font-family: 'DIN Pro Condensed Bold', sans-serif; 
            color: #FFFFFF !important;
            background-color: #a6a8ab !important;
            border: none !important;
            border-radius: 0;
            text-transform: uppercase;
            width: 100%;
        }
        .payment-btn:hover {
            background-color: #41464d !important;
        }

        .btn-payment-selected {
            background-color: #41464d !important;
        }
        .payment-value {
            font-family: 'DIN Pro Condensed Bold', sans-serif !important;
            font-size: 35px;
            color: #41464d;
        }
        .pay-now-btn {
            font-family: 'DIN Pro Condensed Bold', sans-serif; 
            color: #FFFFFF !important;
            background-color: #d22630 !important;
            border: none !important;
            border-radius: 0;
            text-transform: uppercase;
            width: 100%;
        }

        .description-section1 {
            border-left: 2px solid #a6a8ab;
            border-right: 1px solid #a6a8ab;
            background-color: #dcddde;
        }

        .description-section2 {
            border-left: 1px solid #a6a8ab;
            border-right: 2px solid #a6a8ab;
            background-color: #dcddde;
        }
        .license-section1 {
            border-left: 2px solid #a6a8ab;
            border-right: 1px solid #a6a8ab;
        }
        .license-section2 {
            border-left: 1px solid #a6a8ab;
            border-right: 2px solid #a6a8ab;
        }
        .check-fa-icon {
            font-size: 14px;
            color: #37b34a;
        }
        .title-active {
            color: #37b34a;
            font-weight: 700;
        }
    </style>

</head>
<body>

    <div class="mainBannerWrapper">
        <div class="headerSection fixed-header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container" style="background-color: #fff; height: 75px; display: flex; align-items: center;">
                    <a class="navbar-brand" href="{{route('home')}}"><img src="{{ asset('images/ElysiumLogo.png') }}" class="img-fluid" alt="{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif" title="{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif" /></a>
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

    <div class="bg-login-image" id="registrationForm">
      <div class="row" style="margin-top: 75px;">
        <div class="col-lg-4 col-lg-offset-4" style="padding-top: 150px; padding-bottom: 50px;">
            @if($status)
        	<h3 class="info-title text-center">You’ve unsubscribed</h3>
        	<p class="register-desc text-center">You’ll no longer receive emails from Elysiumnetwork about "Tell-A-Friend" messages.</p>
            @else
            <h3 class="info-title text-center">If you want to unsubscribe, please contact <a href="mailto: support@elysiumcapital.io">support@elysiumcapital.io.</a></h3>
            @endif
        </div>
      </div>
    </div>

    <script src="{{ asset('global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/jquery.cookie.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('global/plugins/ladda/spin.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/ladda/ladda.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/User/js/jquery.meanmenu.js')}}"></script>
    @if(isset($scripts) && $scripts)
        @foreach($scripts as $script)
            <script src="{{ $script }}" type="text/javascript"></script>
        @endforeach
    @endif
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
