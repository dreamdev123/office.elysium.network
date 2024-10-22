@extends($layoutPrefix . 'Layout.master')
@inject('hookServices','App\Blueprint\Services\hookServices')
@section('title', $title)
@section('PAGE_LEVEL_STYLES')
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
        /*position: relative;*/
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
        text-align: justify;
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
@endsection
@section('content')
    @inject('htmlHelper', 'App\Blueprint\Services\HtmlServices')
    @php
        $htmlHelper->wrapperClass = 'form-group';
        $htmlHelper->labelClass = 'control-label col-md-3 col-sm-4';
        $htmlHelper->fieldClass = 'col-md-4 col-sm-5';
        $customFieldOptions = [
            'defaultValueMethod' => 'post',
            'text' => [
                'class' => 'form-control',
            ]
        ];

        $month = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $currentyear = date('Y');
    @endphp

    @if(getScope() == 'user' && loggedId())
       @include('_includes.network_nav')
    @endif
    <div class="postRegistrationBox">
        <span class="fa fa-check check"></span>
        <h3>{{_t('register.you_are_registered')}} !!!</h3>
        <div class="registeredInfo">
            {{_t('register.after_reg_text')}}
        </div>
        <div class="registeredFooter">
            {{--<button type="button" class="btn btn-outline blue registrationReceipt ladda-button"--}}
            {{--data-spinner-color="#000" data-style="contract">--}}
            {{--<i class="fa fa-sticky-note"></i>{{_t('register.receipt')}}--}}
            {{--</button>--}}
            <a href="{{ route(strtolower(getScope()).'.logout') }}">
                <button class="btn btn-outline green" type="button">
                    <i class="fa fa-angle-right"></i>{{_t('register.click_to_login')}}
                </button>
            </a>
        </div>
        <div class="invoiceHolder" data-invoice=""></div>
    </div>
    <div class="bg-login-image" id="registrationForm">
        {!! Form::open(['route' => 'admin.register','class' => 'registrationForm', 'data-form' => 'register', 'autocomplete' => 'off']) !!}
        <div class="container" data-backgound="register">
            <div class="row" @if(!loggedId()) style="margin-top: 80px;" @endif >           
                <div class="col-lg-10 col-lg-offset-1" style="padding-top: 50px; padding-bottom: 50px;">
                    <h3 class="text-left register-title pr-5 pl-5">ELYSIUM NETWORK REGISTRATION</h3>
                    <p class="text-left register-subtitle pr-5 pl-5">YOUR REGISTRATION AT ELYSIUM CAPITAL LIMITED</p>
                    <p class="register-desc pr-5 pl-5">This registration is required to access trade signals from our developed Expert Advisors [EA's] which will be installed to the portfolios held in the Multi Account Manager at the connected 3rd party brokerages. Once you've completed this registration you will be advised to the 3rd party brokerage suitable for a specific EA - portfolio.</p>
                    <div class="row pr-5 pl-5" style="margin-top: 50px;">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-right-padding">
                                    <h3 class="info-title text-left">PERSONAL INFO (OFFICIAL DATA ONLY)</h3>
                                    <div class="form-group text-left">
                                        <label for="sponsor" class="label-style">Sponsor</label>
                                        @if ($sponsor && $sponsor_set_by_cookie)
                                        <input type="hidden" name="sponsor" value="{{$sponsor}}" />
                                        @endif
                                        <input type="text" 
                                               class="form-control input-form" 
                                               id="sponsor"
                                               placeholder="Sponsor" 
                                               value="{{ $sponsor ?? '' }}"
                                               @if ($sponsor_set_by_cookie)
                                               disabled="disabled"
                                               readonly="readonly"
                                               name="sponsorLabel" 
                                               @else
                                               name="sponsor" 
                                               @endif
                                        >
                                        <label id="sponsor-error" class="has-error" for="sponsor"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="firstName" class="label-style">Given Name</label>
                                        <input type="text" name="firstname" class="form-control input-form" id="firstName"
                                               placeholder="Given Name" tabindex="1">
                                        <label id="first-name-error" class="has-error" for="first_name"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="lastName" class="label-style">Surname</label>
                                        <input type="text" name="lastname" class="form-control input-form" id="lastName"
                                               placeholder="Surname" tabindex="2">
                                        <label id="last-name-error" class="has-error" for="last_name"
                                               style="display: none"></label>
                                    </div>

                                    <div class="form-group text-left">
                                        <label for="gender" class="label-style">Gender</label>
                                        <div class="d-flex">
                                            <label class="checkbox-container">
                                                <input type="radio" name="gender" id="gender-male"
                                                       checked class="radio-button" value="M" >
                                                <span class="checkmark mr-1 align-middle"></span>
                                                <span class="mb-1">Male</span>
                                            </label>
                                            <label class="checkbox-container ml-3">
                                                <input type="radio" name="gender" id="gender-female"
                                                        class="radio-button" value="F" >
                                                <span class="checkmark mr-1 align-middle"></span>
                                                <span class="mb-1">Female</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="streetName" class="label-style">Street Name</label>
                                        <input type="text" name="street_name" class="form-control input-form" id="streetName"
                                               placeholder="Street Name" tabindex="3" required>
                                        <label id="street-name-error" class="has-error" for="street_name"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="houseNumber" class="label-style">House Number</label>
                                        <input type="text" name="house_no" class="form-control input-form" id="houseNumber"
                                               placeholder="House Number" tabindex="4">
                                        <label id="house-number-error" class="has-error" for="house_number"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="city" class="label-style">City</label>
                                        <input type="text" name="city" class="form-control input-form" id="city"
                                               placeholder="City" tabindex="5">
                                        <label id="city-error" class="has-error" for="city"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="postalCode" class="label-style">Postal Code</label>
                                        <input type="text" name="postcode" class="form-control input-form" id="postalCode" placeholder="Postal Code" tabindex="6" value="{{ \GeoIP::getLocation()['postal_code'] }}">
                                        <label id="postal-code-error" class="has-error" for="postal_code"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="country" class="label-style">Country</label>
                                        <div class="select-bg">
                                            <select class="input-form " id="country" name="country_id" style="padding: 0 16px;">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}"
                                                        @if($country['code'] == \GeoIP::getLocation()['iso_code']) selected @endif>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="mobileNumber" class="label-style">Mobile Number</label>
                                        <input type="text" name="phone" class="form-control input-form" id="mobileNumber" placeholder="Mobile Number" tabindex="7">
                                        <label id="mobile-number-error" class="has-error" for="mobile_number"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="email" class="label-style">E-mail Address</label>
                                        <input type="email" name="email" class="form-control input-form" id="email"
                                               placeholder="E-mail Address" tabindex="8">
                                        <label id="email-error" class="has-error" for="email"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="selectNationality" class="label-style">Nationality</label>
                                        <div class="select-bg">
                                            <select class="input-form" id="selectNationality" name="nationality" style="padding: 0 16px;">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}"
                                                        @if($country['code'] == \GeoIP::getLocation()['iso_code']) selected @endif>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="addressCountry" class="label-style">Address Country</label>
                                        <div class="select-bg">
                                            <select class="input-form" id="addressCountry" name="address_country" style="padding: 0 16px;">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['code'] }}"
                                                        @if($country['code'] == \GeoIP::getLocation()['iso_code']) selected @endif>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 form-left-padding">
                                    <h3 class="info-title text-left">Legal info (Official data only)</h3>
                                    <div class="form-group text-left">
                                        <label for="passportID" class="label-style">Passport or National ID Number</label>
                                        <input type="text" name="passport_no" class="form-control input-form" id="passportID" placeholder="123456789" tabindex="9">
                                        <label id="passport-id-error" class="has-error" for="passport_id"
                                               style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="passportIssuanceDate" class="label-style">Date of Passport or National ID Issuance</label>
                                        <input type="text" name="date_of_passport_issuance" class="form-control input-form" id="passportIssuanceDate" placeholder="YYYY-MM-DD" tabindex="10">
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="passportExpirationDate" class="label-style">Passport or National ID Expiration Date</label>
                                        <input type="text" name="passport_expirition_date" class="form-control input-form" id="passportExpirationDate" placeholder="YYYY-MM-DD" tabindex="11">
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="dateBirth" class="label-style">Date of Birth</label>
                                        <input type="text" name="dob" class="form-control input-form" id="dateBirth" placeholder="YYYY-MM-DD" tabindex="12">
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="countryBirth" class="label-style">Country of Birth</label>
                                        <div class="select-bg">
                                            <select class="input-form" id="countryBirth" name="place_of_birth" style="padding: 0 16px;">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}"
                                                        @if($country['code'] == \GeoIP::getLocation()['iso_code']) selected @endif>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="countryPassport" class="label-style">Country of Passport or National ID Issuance</label>
                                        <div class="select-bg">
                                            <select class="input-form" id="countryPassport" name="country_of_passport_issuance" style="padding: 0 16px;">
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}"
                                                        @if($country['code'] == \GeoIP::getLocation()['iso_code']) selected @endif>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="info-title text-left" style="margin-top: 57px;">Account Info</h3>
                                    <div class="form-group text-left">
                                        <label for="username" class="label-style">Create Username</label>
                                        <input type="text" name="username" class="form-control input-form" id="username" placeholder="Username" tabindex="13">
                                        <label id="username-error" class="has-error" for="username" style="display: none"></label>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="password" class="label-style">Create Password</label>
                                        <div style="position: relative;">
                                            <input type="password" name="password" class="form-control input-form" id="password" placeholder="Password" data-password="register" tabindex="14">
                                            <label id="password-error" class="has-error" for="password"
                                                   style="display: none"></label>
                                            <div class="password-eye" data-password="password-eye">
                                                <i class="fas fa-eye cursor-pointer" data-password="password-eye-show"></i>
                                                <i class="fas fa-eye-slash cursor-pointer" data-password="password-eye-hide"
                                                   style="display: none"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-left">
                                        <label for="passwordConfirmation" class="label-style">Re-type Password</label>
                                        <div style="position: relative;">
                                            <input type="password" name="password_confirmation" class="form-control input-form" id="passwordConfirmation" placeholder="Password" data-password="passwordConfirmation" tabindex="15">
                                            <label id="password-confirmation-error" class="has-error"
                                                   for="password_confirmation" style="display: none"></label>
                                            <div class="password-eye" data-password="passwordConfirmation-eye">
                                                <i class="fas fa-eye cursor-pointer" data-password="passwordConfirmation-eye-show"></i>
                                                <i class="fas fa-eye-slash cursor-pointer" data-password="passwordConfirmation-eye-hide"
                                                   style="display: none"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 bg-white pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="mb-3 text-left payment-choose-title pr-5 pl-5">PLACE YOUR ORDER</div>
                        <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
                            <div class="col-lg-6 col-md-6 pr-0">
                                <div class="d-flex w-100 mt-5 mb-5">
                                    <img src="{{asset('images/EOS.png')}}" style="width: 25%; height: 100%;" />
                                    <img src="{{asset('images/SOHO1.png')}}" style="width: 25%; height: 100%;" />
                                    <img src="{{asset('images/INSIDER.png')}}" style="width: 25%; height: 100%;" />
                                </div>
                                <div class="mb-1 pl-5 info-title text-left">BRAND PARTNER / RESELLER</div>
                                <div class="mb-5 pl-5 info-subtitle text-left">ADVANCED CLOUD SOFTWARE SUITE & APPLICATIONS</div>
                                <div class="d-flex w-100 mb-5 pl-5" >
                                    <div class="text-center">
                                        <label class="checkbox-container">
                                            <input type="radio" name="packageRadio" class="radio-button packageRadio ibPackageRadio" value="2" >
                                            <span class="checkmark1 mr-1"></span>
                                            <span class="info-subtitle">Select This Package</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="w-100 description-section1 pr-5 pl-5">
                                    <div class="d-flex pt-5 mb-3 w-100">
                                        <div class="info-subtitle" style="width: 30%;"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>EOS™</div>
                                        <div class="info-subtitle">ADVANCED OPERATING SYSTEM</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle" style="width: 30%;"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>SOHO™</div>
                                        <div class="info-subtitle">ONE CLICK SOCIAL MEDIA</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle" style="width: 30%;"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>INSIDER™</div>
                                        <div class="info-subtitle">RESEARCH & ANALYSIS</div>
                                    </div>
                                    <div class="d-flex mb-3 w-100">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>ADVANCED ELYSIUM NETWORK WEBSITE</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>ADVANCED ELYSIUM CAPITAL WEBSITE</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>ADVANCED ELYSIUM R&A WEBSITE</div>
                                    </div>
                                    <div class="d-flex mb-3 w-100">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>SINGLE-TIER 'AUM' TRACKING</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>MULTI-TIER 'AUM' TRACKING</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>CRM & BUSINESS TRACKING DISPLAY</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span class="check-fa-icon mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>ADVANCED WEEKLY TRAINING</div>
                                    </div>
                                </div>
                                <div class="w-100 license-section1 bg-white pr-5 pl-5">
                                    <div class="d-flex pt-5 w-100">
                                        <div class="info-subtitle" style="width: 80%;"><b>ONE TIME SOFTWARE LICENSE</b></div>
                                        <div class="info-subtitle"><b>EURO 499,00</b></div>
                                    </div>
                                    <div class="d-flex pb-5 w-100">
                                        <div class="info-subtitle" style="width: 80%;"><b>ONE TIME SET-UP AND ADMIN FEE</b></div>
                                        <div class="info-subtitle"><b>EURO 49,95</b></div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container">
                                                <input type="radio" class="radio-button packageRadio1" disabled>
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle packageRadio1">MEMBERSHIP 1 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%; opacity: 0;"> </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 97,00</div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="10" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%; opacity: 0;"> </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 240,00</div>
                                    </div>
                                    <div class="d-flex pb-3 w-100">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="12" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 12 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%; color: #37b34a;">{{--<b>-10%</b>--}}</div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 880,00</div>
                                    </div>
                                    <div class="d-flex pb-3">
                                        <div class="info-subtitle" style="font-size: 14px; margin-left: auto;">* MEMBERSHIP SUBSCRIPTIONS WILL BE ACTIVATED IN THE 2ND MONTH</div>
                                    </div>
                                </div>
                                {{--<div class="w-100 description-section1 pr-5 pl-5">
                                    <div class="d-flex pt-5 pb-5 w-100">
                                        <div class="info-subtitle"><b>INSIDER RESEARCH & ANALYSIS - SIGNAL REPORTS</b></div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="13" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle ibInsiderMembership1" style="width: 20%;">EURO 199,00</div>
                                        <div class="info-subtitle ibInsiderMembership1_1 title-active" style="width: 20%; display: none;">FREE</div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="14" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 6 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle ibInsiderMembership2" style="width: 20%;">EURO 299,00</div>
                                        <div class="info-subtitle ibInsiderMembership2_1 title-active" style="width: 20%; display: none;">FREE</div>
                                    </div>
                                    <div class="d-flex pb-3 w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="15" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 12 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle ibInsiderMembership3" style="width: 20%;">EURO 499,00</div>
                                        <div class="info-subtitle ibInsiderMembership3_1 title-active" style="width: 20%; display: none;">FREE</div>
                                    </div>
                                    <div class="d-flex pb-3">
                                        <div class="info-subtitle" style="font-size: 14px; margin-left: auto;">* ACTIVE IB’S HAVE FREE R&A SUBSCRIPTION, MATCHING THEIR ELYSIUM MEMBERSHIP</div>
                                    </div>
                                </div>--}}
                                <div class="w-100 bg-white pr-5 pl-5 ib-total-price">
                                    <div class="d-flex pt-5 mb-3 w-100">
                                        <div class="info-subtitle" style="width: 80%;"><b>ORDER TOTAL</b></div>
                                        <div class="info-subtitle"><b class="total-price">EURO 0,00</b></div>
                                    </div>
                                    {{--<div class="ibInsiderMembership1_1" style="display: none;">
                                        <div class="d-flex w-100">
                                            <div class="info-subtitle" style="width: 80%; color: #37b34a;"><b>CONGRATULATIONS - YOU SAVE</b></div>
                                            <div class="info-subtitle" style=" color: #37b34a;"><b>EURO 199,00</b></div>
                                        </div>
                                    </div>
                                    <div class="ibInsiderMembership2_1" style="display: none;">
                                        <div class="d-flex w-100">
                                            <div class="info-subtitle" style="width: 80%; color: #37b34a;"><b>CONGRATULATIONS - YOU SAVE</b></div>
                                            <div class="info-subtitle" style=" color: #37b34a;"><b>EURO 323,00</b></div>
                                        </div>
                                    </div>
                                    <div class="ibInsiderMembership3_1" style="display: none;">
                                        <div class="d-flex w-100">
                                            <div class="info-subtitle" style="width: 80%; color: #37b34a;"><b>CONGRATULATIONS - YOU SAVE</b></div>
                                            <div class="info-subtitle" style=" color: #37b34a;"><b>EURO 595,00</b></div>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 pl-0">
                                <div class="d-flex w-100 mt-5 mb-5">
                                    <img src="{{asset('images/EOS2.png')}}" class="ml-5" style="width: 25%; height: 100%;" />
                                </div>
                                <div class="mb-1 pl-5 info-title text-left">AFFILIATE</div>
                                <div class="mb-5 pl-5 info-subtitle text-left">BASIC CLOUD SOFTWARE</div>
                                <div class="d-flex w-100 mb-5 pl-5" >
                                    <div class="text-center">
                                        <label class="checkbox-container">
                                            <input type="radio" name="packageRadio" class="radio-button packageRadio" value="1">
                                            <span class="checkmark1 mr-1"></span>
                                            <span class="info-subtitle">Select This Package</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="w-100 description-section2 pr-5 pl-5">
                                    <div class="d-flex pt-5 mb-3 w-100">
                                        <div class="info-subtitle" style="width: 30%;"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>EOS™</div>
                                        <div class="info-subtitle">BASIC OPERATING SYSTEM</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle" style="width: 30%;"><span style="font-size: 14px; color: #37b34a; opacity: 0;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>-</div>
                                        <div class="info-subtitle"></div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle" style="width: 30%;"><span style="font-size: 14px; color: #37b34a; opacity: 0;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>-</div>
                                        <div class="info-subtitle"></div>
                                    </div>
                                    <div class="d-flex mb-3 w-100">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>BASIC ELYSIUM NETWORK WEBSITE</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>BASIC ELYSIUM CAPITAL WEBSITE</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>BASIC ELYSIUM R&A WEBSITE</div>
                                    </div>
                                    <div class="d-flex mb-3 w-100">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>SINGLE-TIER 'AUM' TRACKING</div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a; opacity: 0;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>-</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a; opacity: 0;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>-</div>
                                    </div>
                                    <div class="d-flex pb-5">
                                        <div class="info-subtitle"><span style="font-size: 14px; color: #37b34a;" class="mr-3"><i class="fa fa-check" aria-hidden="true"></i></span>ADVANCED WEEKLY TRAINING</div>
                                    </div>
                                </div>
                                <div class="w-100 license-section2 bg-white pr-5 pl-5">
                                    <div class="d-flex pt-5 w-100">
                                        <div class="info-subtitle" style="width: 75%;"><b>SOFTWARE LICENSE</b></div>
                                        <div class="info-subtitle"><b>EURO 199,00</b></div>
                                    </div>
                                    <div class="d-flex pb-5 w-100">
                                        <div class="info-subtitle" style="width: 75%;"><b>ONE TIME SET-UP AND ADMIN FEE</b></div>
                                        <div class="info-subtitle"><b>EURO 49,95</b></div>
                                    </div>
                                    <div class="d-flex w-100" style="opacity: 0;">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container" style="cursor: auto;">
                                                <input type="radio" name="package_membership1" class="radio-button" disabled>
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%;"> </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 240,00</div>
                                    </div>
                                    <div class="d-flex w-100" style="opacity: 0;">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container" style="cursor: auto;">
                                                <input type="radio" name="package_membership1" class="radio-button" disabled>
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%;"> </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 240,00</div>
                                    </div>
                                    <div class="d-flex pb-3 w-100" style="opacity: 0;">
                                        <div style="width: 65%;">
                                            <label class="checkbox-container" style="cursor: auto;">
                                                <input type="radio" name="package_membership1" class="radio-button" disabled>
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 12 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 15%; color: #37b34a;"><b>-10%</b></div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 864,00</div>
                                    </div>
                                    <div class="d-flex pb-3" style="opacity: 0;">
                                        <div class="info-subtitle" style="font-size: 14px; margin-left: auto;">* MEMBERSHIP SUBSCRIPTIONS WILL BE ACTIVATED IN THE 2ND MONTH</div>
                                    </div>
                                </div>
                                {{--<div class="w-100 description-section2 pr-5 pl-5">
                                    <div class="d-flex pt-5 pb-5 w-100">
                                        <div class="info-subtitle"><b>INSIDER RESEARCH & ANALYSIS - SIGNAL REPORTS</b></div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership1" class="radio-button affiliate-insiderRadio" value="7" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 199,00</div>
                                    </div>
                                    <div class="d-flex w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership1" class="radio-button affiliate-insiderRadio" value="8" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 6 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 299,00</div>
                                    </div>
                                    <div class="d-flex pb-3 w-100">
                                        <div style="width: 80%;">
                                            <label class="checkbox-container">
                                                <input type="radio" name="package_insider_membership1" class="radio-button affiliate-insiderRadio" value="9" >
                                                <span class="checkmark1 mr-1"></span>
                                                <span class="info-subtitle">MEMBERSHIP 12 MONTHS</span>
                                            </label>
                                        </div>
                                        <div class="info-subtitle" style="width: 20%;">EURO 499,00</div>
                                    </div>
                                    <div class="d-flex pb-3" style="opacity: 0;">
                                        <div class="info-subtitle" style="font-size: 14px; margin-left: auto;">* ACTIVE IB’S HAVE FREE R&A SUBSCRIPTION, MATCHING THEIR ELYSIUM MEMBERSHIP</div>
                                    </div>
                                </div>--}}
                                <div class="w-100 bg-white pr-5 pl-5 affiliate-total-price">
                                    <div class="d-flex pt-5 mb-3 w-100">
                                        <div class="info-subtitle" style="width: 80%;"><b>ORDER TOTAL</b></div>
                                        <div class="info-subtitle"><b class="total-price">EURO 0,00</b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="container" data-backgound="register">
            <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
                <div class="col-lg-10 col-lg-offset-1 pt-5 pb-5">
                    <div class="mb-3 text-left payment-choose-title pr-5 pl-5">CHOOSE YOUR PAYMENT METHOD</div>
                    <div class="row pt-5 pb-3 pr-5 pl-5">
                        <div class="col-lg-4 col-md-4 p-3" style="display: flex; align-items: center;">
                            <img src="{{asset('images/LogoCards.png')}}" class="logo w-100"/>
                        </div>
                        <div class="col-lg-7 col-md-8 col-lg-offset-1">
                            <div class="row">
                                <?php if(isAdmin()){?>
                                    <div class="col-lg-3 col-md-3 col-sm-3 p-3">
                                       <span class="btn btn-success btn-block btn-lg payment-btn btn-payment" attr_type="Payment-Freejoin" style="margin-left: auto;height:43px;display: flex;width: 100%;  align-items: center;justify-content: center;">Free Pay</span> 
                                    </div>
                                <?php }?>
                                <div class="col-lg-<?php echo isAdmin()?3:4;?> col-md-<?php echo isAdmin()?3:4;?> col-sm-<?php echo isAdmin()?3:4;?> p-3">
                                    <span class="btn btn-success btn-block btn-lg payment-btn btn-payment" attr_type="Payment-SafeCharge">CARD</span>
                                </div>
                                <!-- <div class="col-lg-<?php echo isAdmin()?3:4;?> col-md-<?php echo isAdmin()?3:4;?> col-sm-<?php echo isAdmin()?3:4;?> p-3">
                                    <span class="btn btn-success btn-block btn-lg payment-btn btn-payment" attr_type="Payment-TransferWise">BANK</span>
                                </div> -->
                                <!-- <div class="col-lg-<?php echo isAdmin()?3:4;?> col-md-<?php echo isAdmin()?3:4;?> col-sm-<?php echo isAdmin()?3:4;?> p-3">
                                    <span class="btn btn-success btn-block btn-lg payment-btn btn-payment" attr_type="Payment-B2BPay">CRYPTO</span>
                                </div> -->
                                <input type="hidden" name="selectedPackage" value=""/>
                                <input type="hidden" name="payment" value="IBAN"/>
                                <input type="hidden" name="gateway"/>
                                <input type="hidden" name="context" value="registration" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 pl-5">
                        <div class="form-group text-left">
                            <label class="checkbox-container">
                                <input type="checkbox" name="agreement" class="radio-button" hidden>
                                <span class="checkmark1 mr-1"></span>
                                <span class="mb-1">I agree to the <a href="javascript:;" style="color: black;" data-toggle="modal" data-target="#terms">terms and conditions</a> and the refund and cancellation policy with hyperlinks to the related products</span>
                            </label>
                        </div>
                    </div>
                    <p class="text-center payment-value">ORDER TOTAL: <b class="order-total-price">EURO 0,00</b></p>
                    <div class="row mt-5">
                        <div class="col-md-6 col-md-offset-3">
                            <button class="btn btn-success btn-block btn-lg pay-now-btn" disabled>PAY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="w-100 bg-white" @if((getScope() == 'user' && loggedId()) || (getScope()=='admin' && loggedId() && isAdmin())) style="display: none" @endif>
            <div class="footer-disclaimer">
                <div class="container">
                    <p style="margin-top: 0px" class="disclaimer-title">DISCLAIMER</p>
                    <p style="margin-top: 20px" class="disclaimer-desc">ELYSIUM CAPITAL LTD AND/OR ITS SUBSIDIARIES ARE NOT DIRECTLY OR INDIRECTLY INVOLVED IN THE OFFERING OF SECURITIES.</p>
                    <p style="margin-top: 20px;" class="disclaimer-desc">We are a Fintech access provider and signal provider. We develop trade signals per quantitative analysis. Clients engage our EA's to execute their trades via 3rd party brokerages. Clients can at all times manually intervene in open positions c.q. our portfolios. We grant access to the data on trading portfolio operations allowing other participants to auto-copy signals automatically via their EA on their own trading accounts via a 3rd party independent prime brokerage, not being Elysium Capital Limited or any of our subsidiaries.</p>
                    <p style="margin-top: 20px;" class="disclaimer-desc">The prime brokerages we introduce our participants to are regulated in several jurisdictions. Participants can, at their own discretion and liability, appoint a proprietary trading desk to trade on their behalf by providing a signed limited power of attorney subject to compliance approval. Their money is held in a segregated account with that 3rd party brokerage. Participants have full control over entries and exits. Past performance is not an indication of future results. This is not investment advice. All transactions on the currency spot market are speculative and all investments should be made using risk capital that is not crucially required. There may be a considerable risk of losses on the currency spot market and all transactions are at risk of capital loss. You should consider carefully whether such participation is appropriate to you when taking into account your financial assets. We advise everyone to seek independent advice regarding participation in the currency spot market. No information on this website should be understood to constitute financial advice from Elysium Capital Limited. It is published solely for information and marketing purposes. The 3rd party brokerages do not accept clients from the U.S, Iran, Syria, North Korea, Yemen, and Cuba. They may reject any applicant from any jurisdiction at their sole discretion without the requirement to explain the reason why. Affiliates – Brand Partners earn performance fees, if derived, on participants and do not facilitate transactions and therefore can’t be involved in the receiving and transmitting of orders. The information in this paper, website, is provided for general informational purposes only, and may not reflect the current law in your jurisdiction. A Multi Account Manager (MAM) is designed to provide the professional trading desk with essential integrated software tools to quickly and conveniently allocate and auto-copy our signals under the master account arrangement in live trading conditions. In some jurisdictions, the act of referring clients are deemed to be regulated activities and, as such, require the BRAND PARTNER (IB) to have the necessary regulatory authorisation. It is the duty as an IB to ensure that they only carry out activities and provide services in accordance with local laws and regulations.</p>
                </div>
            </div>
        </div>
    </div>
    <div id="terms" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Terms of Use</h3>
                </div>
                <div class="modal-body">
                    @include('_includes.terms_of_use')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="safecharge-error">

        <div class="modal-dialog modal-dialog-centered" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                We are currently unable to handle credit card payments. Kindly make IBAN / SWIFT Transfers during this time.
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal" id="iban-alert">

        <div class="modal-dialog modal-dialog-centered" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                After making a payment, please let the Support(<span class="text-primary">support@elysiumnetwork.io</span>) know that the payment was done successfully.
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="formular_content" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="content" id="formular_content_content"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="tab6" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                <div class="paycontainer">
                    <div class="row" style="display: flex; align-items: center;">
                        <div class="col-sm-8">
                            <div class="row" style="display: flex;align-items: center;">
                                <div class="col-sm-6">
                                    <img src="{{asset('images/ElysiumLogo.png')}}" class="logo"/>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="logocolor" style="font-weight: 600 !important;text-align: center;">Invoice For Payment</h4>
                                </div>    
                            </div>
                        </div>
                        <div class="col-sm-4" style="text-align: center;">
                            <img src="{{asset('images/bitcoin.png')}}" class="bitcoin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="content" id="payment_result"></div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
@endsection

@section('PAGE_LEVEL_SCRIPTS')

    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        const register = {
            init: function () {
                this.variables();
                this.addEventListeners();
                this.firstInputFocus();
                // this.showRegisterPage();
                this.datePicker();
            },
            variables: function () {
                this.passwordRegisterInput = $('[data-password="register"]');
                this.passwordRegisterEye = $('[data-password="password-eye"]');
                this.passwordRegisterEyeShow = $('[data-password="password-eye-show"]');
                this.passwordRegisterEyeHide = $('[data-password="password-eye-hide"]');
                this.passwordConfirmationInput = $('[data-password="passwordConfirmation"]');
                this.passwordConfirmationEye = $('[data-password="passwordConfirmation-eye"]');
                this.passwordConfirmationEyeShow = $('[data-password="passwordConfirmation-eye-show"]');
                this.passwordConfirmationEyeHide = $('[data-password="passwordConfirmation-eye-hide"]');
                this.form = $('[data-form="register"]');
                this.affiliateInput = $('#affiliate');
                this.firstNameInput = $('#firstName');
                this.firstNameError = $('#first-name-error');
                this.lastNameInput = $('#lastName');
                this.lastNameError = $('#last-name-error');
                this.streetNameInput = $('#streetName');
                this.streetNameError = $('#street-name-error');
                this.houseNumberInput = $('#houseNumber');
                this.houseNumberError = $('#house-number-error');
                this.cityInput = $('#city');
                this.cityError = $('#city-error');
                this.postalCodeInput = $('#postalCode');
                this.postalCodeError = $('#postal-code-error');
                this.mobileNumberInput = $('#mobileNumber');
                this.mobileNumberError = $('#mobile-number-error');
                this.passportIDInput = $('#passportID');
                this.passportIDError = $('#passport-id-error');
                this.emailInput = $('#email');
                this.emailError = $('#email-error');
                this.passwordInput = $('#password');
                this.passwordError = $('#password-error');
                this.passwordConfirmInput = $('#passwordConfirmation');
                this.passwordConfirmError = $('#password-confirmation-error');
                this.dateBirth = $('#dateBirth');
                this.passportExpirationDate = $('#passportExpirationDate');
                this.passportIssuanceDate = $('#passportIssuanceDate');
                this.usernameInput = $('#username');
                this.usernameError = $('#username-error');
                this.scrollToError = '';
                this.submitButton = $('.pay-now-btn');
            },
            addEventListeners: function () {
                this.passwordRegisterEye.on('click', function () {
                    this.togglePasswordVisibility();
                }.bind(this));
                this.passwordConfirmationEye.on('click', function () {
                    this.togglePasswordConfirmationVisibility();
                }.bind(this));
                this.firstNameInput.on('keyup', function () {
                    this.validateFirstNameInput();
                }.bind(this));
                this.lastNameInput.on('keyup', function () {
                    this.validateLastNameInput();
                }.bind(this));
                this.emailInput.on('keyup', function () {
                    this.validateEmailInput();
                }.bind(this));
                this.passwordInput.on('keyup', function () {
                    this.validatePasswordInput();
                }.bind(this));
                this.passwordConfirmInput.on('keyup', function () {
                    this.validatePasswordConfirmationInput();
                }.bind(this));
                this.streetNameInput.on('keyup', function () {
                    this.validateStreetNameInput();
                }.bind(this));
                this.houseNumberInput.on('keyup', function () {
                    this.validateHouseNumberInput();
                }.bind(this));
                this.cityInput.on('keyup', function () {
                    this.validateCityInput();
                }.bind(this));
                this.postalCodeInput.on('keyup', function () {
                    this.validatePostalCodeInput();
                }.bind(this));
                this.mobileNumberInput.on('keyup', function () {
                    this.validateMobileNumberInput();
                }.bind(this));
                this.passportIDInput.on('keyup', function () {
                    this.validatePassportIDInput();
                }.bind(this));
                this.usernameInput.on('keyup', function () {
                    this.validateUsernameInput();
                }.bind(this));
                this.form.on('submit', function (event) {
                    if (this.validateFirstNameInput() &&
                        this.validateLastNameInput() &&
                        this.validateEmailInput() &&
                        this.validatePasswordInput() &&
                        this.validatePasswordConfirmationInput() &&
                        this.validateStreetNameInput() &&
                        this.validateHouseNumberInput() &&
                        this.validateCityInput() &&
                        this.validatePostalCodeInput() &&
                        this.validateMobileNumberInput() &&
                        this.validatePassportIDInput() &&
                        this.validateUsernameInput()) {
                        return true;
                    } else {
                        event.preventDefault();
                        this.scrollToElement(this.scrollToError);
                        this.scrollToError.focus();
                    }
                }.bind(this));
                // this.submitButton.on('click', function (event) {
                //     this.form.submit();
                // }.bind(this));
                // $(document).on('keypress', function (e) {
                //     if ((e.which === 13)) {
                //         this.form.submit();
                //     }
                // }.bind(this));
            },
            scrollToElement: function (element) {
                $('body, html').animate({
                    scrollTop: element.offset().top - 50
                }, 500);
            },
            togglePasswordVisibility: function () {
                if (this.passwordRegisterInput.attr('type') === "password") {
                    this.passwordRegisterInput.attr('type', 'text');
                    this.passwordRegisterEyeShow.hide();
                    this.passwordRegisterEyeHide.show();
                } else {
                    this.passwordRegisterInput.attr('type', 'password');
                    this.passwordRegisterEyeShow.show();
                    this.passwordRegisterEyeHide.hide();
                }
            },
            togglePasswordConfirmationVisibility: function () {
                if (this.passwordConfirmationInput.attr('type') === "password") {
                    this.passwordConfirmationInput.attr('type', 'text');
                    this.passwordConfirmationEyeShow.hide();
                    this.passwordConfirmationEyeHide.show();
                } else {
                    this.passwordConfirmationInput.attr('type', 'password');
                    this.passwordConfirmationEyeShow.show();
                    this.passwordConfirmationEyeHide.hide();
                }
            },
            firstInputFocus: function () {
                setTimeout(function () {
                    this.firstNameInput.focus();
                }.bind(this), 300);
            },
            validateFirstNameInput: function () {
                var validationMessage = '';
                var formControl = this.firstNameInput.parent('.form-group').find('.form-control');
                var value = this.firstNameInput.val();

                if ((/^[a-zA-Z\-0-9 ]{2,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good first name.\n';
                    formControl.addClass('valid');
                    this.firstNameError.addClass('valid');
                    this.firstNameError.show();
                } else if (value === '') {
                    validationMessage = 'The given name field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.firstNameError.removeClass('valid');
                    this.firstNameError.show();
                } else {
                    validationMessage = 'The given name must contain only letter and be minimum of 2 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.firstNameError.removeClass('valid');
                    this.firstNameError.show();
                }

                this.firstNameError.html(validationMessage);
                this.scrollToError = this.firstNameInput;

                return ((/^[a-zA-Z\-0-9 ]{2,50}$/.test(value)));
            },
            validateLastNameInput: function () {
                var validationMessage = '';
                var formControl = this.lastNameInput.parent('.form-group').find('.form-control');
                var value = this.lastNameInput.val();

                if ((/^[a-zA-Z\-0-9 ]{2,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good surname.\n';
                    formControl.addClass('valid');
                    this.lastNameError.addClass('valid');
                    this.lastNameError.show();
                } else if (value === '') {
                    validationMessage = 'The surname field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.lastNameError.removeClass('valid');
                    this.lastNameError.show();
                } else {
                    validationMessage = 'The surname must contain only letter and be minimum of 2 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.lastNameError.removeClass('valid');
                    this.lastNameError.show();
                }

                this.lastNameError.html(validationMessage);
                this.scrollToError = this.lastNameInput;

                return ((/^[a-zA-Z\-0-9 ]{2,50}$/.test(value)));
            },
            validateUsernameInput: function () {
                var validationMessage = '';
                var formControlEmail = this.usernameInput.parent('.form-group').find('.form-control');
                var value = this.usernameInput.val();
                var options = {action: 'verifyUsername', data: {username: value}};
                var post = $.post(userApi, options);
                var self = this;
                post.done(function (response) {
                    if (response.status) {
                        if ((/^.{6,50}$/.test(value))) {
                            validationMessage = 'Now, that\'s a good username.\n';
                            formControlEmail.addClass('valid');
                            self.usernameError.addClass('valid');
                            self.usernameError.show();
                        } else if (value === '') {
                            validationMessage = 'The username field is required.';
                            formControlEmail.removeClass('valid');
                            formControlEmail.addClass('has-error');
                            self.usernameError.removeClass('valid');
                            self.usernameError.show();
                        } else {
                            validationMessage = 'Username must contain letter and number and be minimum of 6 characters.';
                            formControlEmail.removeClass('valid');
                            formControlEmail.addClass('has-error');
                            self.usernameError.removeClass('valid');
                            self.usernameError.show();
                        }

                        self.usernameError.html(validationMessage);
                        self.scrollToError = self.usernameInput;

                        return ((/^.{6,50}$/.test(value)));
                    } else {
                        validationMessage = response.message;
                        formControlEmail.removeClass('valid');
                        formControlEmail.addClass('has-error');
                        self.usernameError.removeClass('valid');
                        self.usernameError.show();

                        self.usernameError.html(validationMessage);
                        self.scrollToError = self.usernameInput;

                        return ((/^.{6,50}$/.test(value)));
                    }
                });

                return post;
            },
            validateEmailInput: function () {
                var validationMessage = '';
                var formControlEmail = this.emailInput.parent('.form-group').find('.form-control');
                var value = this.emailInput.val();

                var options = {action: 'verifyEmail', data: {email: value}};
                var post = $.post(userApi, options);
                var self = this;
                post.done(function (response) {
                    if (response.status) {
                        if ((/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value))) {
                            validationMessage = 'Now, that\'s a good email.\n';
                            formControlEmail.addClass('valid');
                            self.emailError.addClass('valid');
                            self.emailError.show();
                        } else if (value === '') {
                            validationMessage = 'The email field is required.';
                            formControlEmail.removeClass('valid');
                            formControlEmail.addClass('has-error');
                            self.emailError.removeClass('valid');
                            self.emailError.show();
                        } else {
                            validationMessage = 'This email is not valid.';
                            formControlEmail.removeClass('valid');
                            formControlEmail.addClass('has-error');
                            self.emailError.removeClass('valid');
                            self.emailError.show();
                        }

                        self.emailError.html(validationMessage);
                        self.scrollToError = self.emailInput;

                        return ((/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)));

                        return ((/^.{6,50}$/.test(value)));
                    } else {
                        validationMessage = response.message;
                        formControlEmail.removeClass('valid');
                        formControlEmail.addClass('has-error');
                        self.emailError.removeClass('valid');
                        self.emailError.show();

                        self.emailError.html(validationMessage);
                        self.scrollToError = self.emailInput;

                        return ((/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)));
                    }
                });

                return post;
            },
            validatePasswordInput: function () {
                var validationMessage = '';
                var value = this.passwordInput.val();

                if ((/^[a-zA-Z\-0-9 ]{7,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a secure password.\n';
                    this.errorRegisterPassword();
                } else if (value === '') {
                    validationMessage = 'Password must contain a <strong>letter</strong> or a <strong>number</strong>, and be minimum of <strong>7 characters</strong>.';
                    this.validRegisterPassword();
                } else {
                    validationMessage = 'Password must contain a <strong>letter</strong> or a <strong>number</strong>, and be minimum of <strong>7 characters</strong>.';
                    this.validRegisterPassword();
                }

                this.passwordError.html(validationMessage);
                this.scrollToError = this.passwordInput;

                return ((/^[a-zA-Z\-0-9 ]{7,50}$/.test(value)));
            },
            validatePasswordConfirmationInput: function () {
                var validationMessage = '';
                var formControlPassConfirm = this.passwordConfirmInput.parent('.form-group').find('.form-control');
                var value = this.passwordConfirmInput.val();

                if (value === this.passwordInput.val()) {
                    validationMessage = 'Now, that\'s a good password confirmation.\n';
                    formControlPassConfirm.addClass('valid');
                    this.passwordConfirmError.addClass('valid');
                    this.passwordConfirmError.show();
                } else if (value === '') {
                    validationMessage = 'The password confirmation field is required.';
                    formControlPassConfirm.removeClass('valid');
                    formControlPassConfirm.addClass('has-error');
                    this.passwordConfirmError.removeClass('valid');
                    this.passwordConfirmError.show();
                } else {
                    validationMessage = 'The password confirmation and password must match.';
                    formControlPassConfirm.removeClass('valid');
                    formControlPassConfirm.addClass('has-error');
                    this.passwordConfirmError.removeClass('valid');
                    this.passwordConfirmError.show();
                }

                this.passwordConfirmError.html(validationMessage);
                this.scrollToError = this.passwordConfirmInput;

                return (value === this.passwordInput.val());
            },
            validRegisterPassword: function () {
                var formControlPassword = this.passwordInput.parent('.form-group').find('.form-control');
                formControlPassword.removeClass('valid');
                formControlPassword.addClass('has-error');
                this.passwordError.removeClass('valid');
                this.passwordError.show();
            },
            errorRegisterPassword: function () {
                var formControlPassword = this.passwordInput.parent('.form-group').find('.form-control');
                formControlPassword.addClass('valid');
                this.passwordError.addClass('valid');
                this.passwordError.show();
            },
            validateStreetNameInput: function () {
                var validationMessage = '';
                var formControl = this.streetNameInput.parent('.form-group').find('.form-control');
                var value = this.streetNameInput.val();

                if ((/^[a-zA-Z\-0-9 ]{3,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good street name.\n';
                    formControl.addClass('valid');
                    this.streetNameError.addClass('valid');
                    this.streetNameError.show();
                } else if (value === '') {
                    validationMessage = 'The street name field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.streetNameError.removeClass('valid');
                    this.streetNameError.show();
                } else {
                    validationMessage = 'The street name must contain letter and number and be minimum of 3 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.streetNameError.removeClass('valid');
                    this.streetNameError.show();
                }

                this.streetNameError.html(validationMessage);
                this.scrollToError = this.streetNameInput;

                return ((/^[a-zA-Z\-0-9 ]{3,50}$/.test(value)));
            },
            validateHouseNumberInput: function () {
                var validationMessage = '';
                var formControl = this.houseNumberInput.parent('.form-group').find('.form-control');
                var value = this.houseNumberInput.val();

                if ((/^.{1,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good house number.\n';
                    formControl.addClass('valid');
                    this.houseNumberError.addClass('valid');
                    this.houseNumberError.show();
                } else if (value === '') {
                    validationMessage = 'The house number field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.houseNumberError.removeClass('valid');
                    this.houseNumberError.show();
                } else {
                    validationMessage = 'The house number must contain letter and number and be minimum of 3 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.houseNumberError.removeClass('valid');
                    this.houseNumberError.show();
                }

                this.houseNumberError.html(validationMessage);
                this.scrollToError = this.houseNumberInput;

                return ((/^.{1,50}$/.test(value)));
            },
            validateCityInput: function () {
                var validationMessage = '';
                var formControl = this.cityInput.parent('.form-group').find('.form-control');
                var value = this.cityInput.val();

                if ((/^[a-zA-Z\-0-9 ]{3,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good city name.\n';
                    formControl.addClass('valid');
                    this.cityError.addClass('valid');
                    this.cityError.show();
                } else if (value === '') {
                    validationMessage = 'The city name field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.cityError.removeClass('valid');
                    this.cityError.show();
                } else {
                    validationMessage = 'The city name must contain letter and number and be minimum of 3 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.cityError.removeClass('valid');
                    this.cityError.show();
                }

                this.cityError.html(validationMessage);
                this.scrollToError = this.cityInput;

                return ((/^[a-zA-Z\-0-9 ]{3,50}$/.test(value)));
            },
            validatePostalCodeInput: function () {
                var validationMessage = '';
                var formControl = this.postalCodeInput.parent('.form-group').find('.form-control');
                var value = this.postalCodeInput.val();

                if ((/^.{3,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good postal code.\n';
                    formControl.addClass('valid');
                    this.postalCodeError.addClass('valid');
                    this.postalCodeError.show();
                } else if (value === '') {
                    validationMessage = 'The postal code field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.postalCodeError.removeClass('valid');
                    this.postalCodeError.show();
                } else {
                    validationMessage = 'The postal code must contain letter and number and be minimum of 3 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.postalCodeError.removeClass('valid');
                    this.postalCodeError.show();
                }

                this.postalCodeError.html(validationMessage);
                this.scrollToError = this.postalCodeInput;

                return ((/^.{3,50}$/.test(value)));
            },
            validateMobileNumberInput: function () {
                var validationMessage = '';
                var formControl = this.mobileNumberInput.parent('.form-group').find('.form-control');
                var value = this.mobileNumberInput.val();

                if ((/^[0-9]{7,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good mobile number.\n';
                    formControl.addClass('valid');
                    this.mobileNumberError.addClass('valid');
                    this.mobileNumberError.show();
                } else if (value === '') {
                    validationMessage = 'The mobile number field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.mobileNumberError.removeClass('valid');
                    this.mobileNumberError.show();
                } else {
                    validationMessage = 'The mobile number must contain only number and be minimum of 7 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.mobileNumberError.removeClass('valid');
                    this.mobileNumberError.show();
                }

                this.mobileNumberError.html(validationMessage);
                this.scrollToError = this.mobileNumberInput;

                return ((/^[0-9]{7,50}$/.test(value)));
            },
            validatePassportIDInput: function () {
                var validationMessage = '';
                var formControl = this.passportIDInput.parent('.form-group').find('.form-control');
                var value = this.passportIDInput.val();

                if ((/^[a-zA-Z\-0-9]{7,50}$/.test(value))) {
                    validationMessage = 'Now, that\'s a good passport ID.\n';
                    formControl.addClass('valid');
                    this.passportIDError.addClass('valid');
                    this.passportIDError.show();
                } else if (value === '') {
                    validationMessage = 'The passport ID field is required.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.passportIDError.removeClass('valid');
                    this.passportIDError.show();
                } else {
                    validationMessage = 'The passport ID must contain only letter and be minimum of 7 characters.';
                    formControl.removeClass('valid');
                    formControl.addClass('has-error');
                    this.passportIDError.removeClass('valid');
                    this.passportIDError.show();
                }

                this.passportIDError.html(validationMessage);
                this.scrollToError = this.passportIDInput;

                return ((/^[a-zA-Z\-0-9]{7,50}$/.test(value)));
            },
            datePicker: function () {
                this.dateBirth.datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    endDate: '-18y',
                    format: 'yyyy-mm-dd',
                    showOnFocus: true
                }).on('hide', function () {
                    if (!this.firstHide) {
                        if (!$(this).is(":focus")) {
                            this.firstHide = true;
                            // this will inadvertently call show (we're trying to hide!)
                            this.focus();
                        }
                    } else {
                        this.firstHide = false;
                    }
                }).on('show', function () {
                    if (this.firstHide) {
                        // careful, we have an infinite loop!
                        $(this).datepicker('hide');
                    }
                });

                this.passportIssuanceDate.datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    startDate: '-40y',
                    endDate: '-0y',
                    format: 'yyyy-mm-dd',
                    showOnFocus: true
                }).on('hide', function () {
                    if (!this.firstHide) {
                        if (!$(this).is(":focus")) {
                            this.firstHide = true;
                            // this will inadvertently call show (we're trying to hide!)
                            this.focus();
                        }
                    } else {
                        this.firstHide = false;
                    }
                }).on('show', function () {
                    if (this.firstHide) {
                        // careful, we have an infinite loop!
                        $(this).datepicker('hide');
                    }
                });

                this.passportExpirationDate.datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    startDate: '+1d',
                    endDate: '+40y',
                    format: 'yyyy-mm-dd',
                    showOnFocus: true
                }).on('hide', function () {
                    if (!this.firstHide) {
                        if (!$(this).is(":focus")) {
                            this.firstHide = true;
                            // this will inadvertently call show (we're trying to hide!)
                            this.focus();
                        }
                    } else {
                        this.firstHide = false;
                    }
                }).on('show', function () {
                    if (this.firstHide) {
                        // careful, we have an infinite loop!
                        $(this).datepicker('hide');
                    }
                })
            },
        };

        $(document).ready(function () {
            register.init();
            // if(!$('input[name=sponsor]').val())
            // {
                alert('Please contact support@elysiumnetwork.io.');
                document.location.href = "{{route('home')}}";
            // }
            $('.packageRadio').prop('checked', false);
            $('.packageRadio1').prop('checked', false);
            $('.subscriptionRadio').prop('checked', false);
            $('.ib-insiderRadio').prop('checked', false);
            $('.subscriptionRadio').attr('disabled', true);
            $('.ib-insiderRadio').attr('disabled', true);
            $('.affiliate-insiderRadio').prop('checked', false);
            $('.affiliate-insiderRadio').attr('disabled', true);
            $('.ib-total-price').hide();
            $('.affiliate-total-price').hide();
        });

        $('.packageRadio').change(function(){
          if( $(this).is(":checked") ) {
            var options = {};
            options.productId = $(this).val();
            options.quantity = 1;
            options.isPackage = true;
            $.ajax({
                type: 'POST',
                url: cartAddRoute,
                data: options,
                success: function(data) {
                    $('input[name=selectedPackage]').val(data.item.id);
                    var total_price = data.total_amount;
                        total_price = 'EURO ' + total_price;
                    $('.ibInsiderMembership1').show();
                    $('.ibInsiderMembership1_1').hide();
                    $('.ibInsiderMembership2').show();
                    $('.ibInsiderMembership2_1').hide();
                    $('.ibInsiderMembership3').show();
                    $('.ibInsiderMembership3_1').hide(); 
                    if (options.productId == 2) {
                        $('.packageRadio1').prop('checked', true);
                        $('.affiliate-insiderRadio').prop('checked', false);
                        $('.affiliate-insiderRadio').attr('disabled', true);
                        $('.subscriptionRadio').attr('disabled', false);
                        $('.ib-insiderRadio').attr('disabled', false);
                        $('.ib-total-price').find('.total-price').eq(0).html(total_price);
                        $('.ib-total-price').show();
                        $('.affiliate-total-price').hide();
                        $('.order-total-price').html(total_price);
                    } else if (options.productId == 1) {
                        $('.packageRadio1').prop('checked', false);
                        $('.subscriptionRadio').prop('checked', false);
                        $('.subscriptionRadio').attr('disabled', true);
                        $('.ib-insiderRadio').prop('checked', false);
                        $('.ib-insiderRadio').attr('disabled', true);
                        $('.affiliate-insiderRadio').attr('disabled', false);
                        $('.affiliate-total-price').find('.total-price').eq(0).html(total_price);
                        $('.ib-total-price').hide();
                        $('.affiliate-total-price').show();
                        $('.order-total-price').html(total_price);
                    }

                        activePayNowBTN();
                },
                error: function(data){
                  console.log(data);
                }
            })
          }
        });

        $('.packageRadio1').click(function(){
            var options = {};
            options.productId = 2;
            options.quantity = 1;
            options.isPackage = true;
            $.ajax({
                type: 'POST',
                url: cartAddRoute,
                data: options,
                success: function(data) {
                    $('input[name=selectedPackage]').val(data.item.id);
                    var total_price = data.total_amount;
                        total_price = 'EURO ' + total_price;
                    $('.ibInsiderMembership1').show();
                    $('.ibInsiderMembership1_1').hide();
                    $('.ibInsiderMembership2').show();
                    $('.ibInsiderMembership2_1').hide();
                    $('.ibInsiderMembership3').show();
                    $('.ibInsiderMembership3_1').hide(); 
                    if (options.productId == 2) {
                        $('.ibPackageRadio').prop('checked', true);
                        $('.packageRadio1').prop('checked', true);
                        $('.subscriptionRadio').prop('checked', false);
                        $('.affiliate-insiderRadio').prop('checked', false);
                        $('.affiliate-insiderRadio').attr('disabled', true);
                        $('.subscriptionRadio').attr('disabled', false);
                        $('.ib-insiderRadio').attr('disabled', false);
                        $('.ib-total-price').find('.total-price').eq(0).html(total_price);
                        $('.ib-total-price').show();
                        $('.affiliate-total-price').hide();
                        $('.order-total-price').html(total_price);
                    } else if (options.productId == 1) {
                        $('.packageRadio1').prop('checked', false);
                        $('.subscriptionRadio').prop('checked', false);
                        $('.subscriptionRadio').attr('disabled', true);
                        $('.ib-insiderRadio').prop('checked', false);
                        $('.ib-insiderRadio').attr('disabled', true);
                        $('.affiliate-insiderRadio').attr('disabled', false);
                        $('.affiliate-total-price').find('.total-price').eq(0).html(total_price);
                        $('.ib-total-price').hide();
                        $('.affiliate-total-price').show();
                        $('.order-total-price').html(total_price);
                    }

                    activePayNowBTN();
                },
                error: function(data){
                  console.log(data);
                }
            })
        });

        $('.affiliate-insiderRadio').change(function(){
            if( $(this).is(":checked") ) {
                var options = {};
                options.productId = $(this).val();
                options.quantity = 1;
                options.isPackage = true;
                $.ajax({
                    type: 'POST',
                    url: cartAddRoute,
                    data: options,
                    success: function(data) {
                        $('input[name=selectedPackage]').val(data.item.id);
                        var total_price = data.total_amount;
                            total_price = 'EURO ' + total_price + ',00';
                        $('.affiliate-total-price').find('.total-price').eq(0).html(total_price);
                        $('.ib-total-price').hide();
                        $('.affiliate-total-price').show();
                        $('.order-total-price').html(total_price);

                        activePayNowBTN();
                    },
                    error: function(data){
                      console.log(data);
                    }
                })
            }
        });

        $('.subscriptionRadio').change(function(){
            if( $(this).is(":checked") ) {
                var options = {};
                options.productId = $(this).val();
                options.quantity = 1;
                options.isPackage = true;
                $.ajax({
                    type: 'POST',
                    url: cartAddRoute,
                    data: options,
                    success: function(data) {
                        $('input[name=selectedPackage]').val(data.item.id);
                        var total_price = data.total_amount;
                            total_price = 'EURO ' + total_price;
                        $('.packageRadio1').prop('checked', false);
                        $('.ibInsiderMembership1').show();
                        $('.ibInsiderMembership1_1').hide();
                        $('.ibInsiderMembership2').show();
                        $('.ibInsiderMembership2_1').hide();
                        $('.ibInsiderMembership3').show();
                        $('.ibInsiderMembership3_1').hide(); 
                        if (options.productId == 10) {
                            $("input[name=package_insider_membership][value=" + 13 + "]").prop('checked', true);
                            $('.ibInsiderMembership1').hide();
                            $('.ibInsiderMembership1_1').show();
                        } else if (options.productId == 11) {
                            $("input[name=package_insider_membership][value=" + 14 + "]").prop('checked', true);
                            $('.ibInsiderMembership2').hide();
                            $('.ibInsiderMembership2_1').show();
                        } else if (options.productId == 12) {
                            $("input[name=package_insider_membership][value=" + 15 + "]").prop('checked', true);
                            $('.ibInsiderMembership3').hide();
                            $('.ibInsiderMembership3_1').show();
                        }
                        $('.ib-total-price').find('.total-price').eq(0).html(total_price);
                        $('.order-total-price').html(total_price);

                        activePayNowBTN();
                    },
                    error: function(data){
                      console.log(data);
                    }
                })
            }
        });

        $('.ib-insiderRadio').change(function(){
            if( $(this).is(":checked") ) {
                var options = {};
                options.productId = $(this).val();
                options.quantity = 1;
                options.isPackage = true;
                $.ajax({
                    type: 'POST',
                    url: cartAddRoute,
                    data: options,
                    success: function(data) {
                        $('input[name=selectedPackage]').val(data.item.id);
                        var total_price = data.total_amount;
                            total_price = 'EURO ' + total_price;
                        $('.ibInsiderMembership1').show();
                        $('.ibInsiderMembership1_1').hide();
                        $('.ibInsiderMembership2').show();
                        $('.ibInsiderMembership2_1').hide();
                        $('.ibInsiderMembership3').show();
                        $('.ibInsiderMembership3_1').hide();
                        $('.subscriptionRadio').prop('checked', false);
                        $('.ib-total-price').find('.total-price').eq(0).html(total_price);
                        $('.order-total-price').html(total_price);

                        activePayNowBTN();
                    },
                    error: function(data){
                      console.log(data);
                    }
                })
            }
        });
        function activePayNowBTN() {
            var agreementChecked = $("input[name=agreement]:checked").val();
            let selectedPackage = $('input[name=selectedPackage]').val();
            if (agreementChecked && selectedPackage > 0 && $('.btn-payment').hasClass('btn-payment-selected')) {
                $('.pay-now-btn').attr('disabled', false);
            } else {
                $('.pay-now-btn').attr('disabled', true);
            }
        }
        
        $('input[name=agreement]').change(function(){
            let checked = this.checked;
            let selectedPackage = $('input[name=selectedPackage]').val();
            if (checked && selectedPackage > 0 && $('.btn-payment').hasClass('btn-payment-selected')) {
                $('.pay-now-btn').attr('disabled', false);
            } else {
                $('.pay-now-btn').attr('disabled', true);
            }
        })
        $('.btn-payment').click(function(){
            $('.btn-payment').each(function(){
                $(this).removeClass('btn-payment-selected');
            })

            $(this).addClass('btn-payment-selected');

            if($('.pay-now-btn').hasClass('package_upgrade'))
            {
                $('#upgrade_content').modal('show');
            } else {
                if ($(this).attr('attr_type') != 'Payment-Freejoin') {
                    $('#iban-alert').modal('show');
                }
            }
            activePayNowBTN();
        })

        

        $('select[name=country_id]').change(function(){
            $.get('{{route("transferwise.countryfilter")}}',{country:$(this).val()},function(res){
                res = JSON.parse(res);
                if(res.success)
                {
                    $('.btn-payment[attr_type=Payment-TransferWise]').html('IBAN');
                }   
                else
                {
                    $('.btn-payment[attr_type=Payment-TransferWise]').html('SWIFT');
                }
            })
        })

        $('.pay-now-btn').unbind('click').click(function(){

            if($(this).hasClass('b2bpay'))
            {
                var form = document.createElement('form');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name  = 'address';
                input.value = $('.pay-address').html();
                form.appendChild(input);

                form.method = "POST";
                form.action = $(this).attr('href');

                document.body.appendChild(form);
                form.submit();
                return false;
            }
            var gateway = $('.btn-payment-selected').attr('attr_type');

            var payment  = "";


            if($('.btn-payment-selected').html() == 'IBAN')
            {
                payment = 'IBAN';
            }
            else if($('.btn-payment-selected').html() == 'SWIFT')
            {
                payment = "SWIFT";
            }

            if($(this).attr('attr_type') == 'freepay')
            {
                gateway = 'Payment-Freejoin';
            }

            $.get("{{route(getScope() . '.getGatewayitem')}}",{context:$('input[name=context]').val(),gateway:gateway}).then(res=>{
                $('input[name=gateway]').val(res.id);
                
                var options = {};

                if(gateway == 'Payment-B2BPay')
                {
                    options = {
                        actionUrl: '{{route(getScope() . ".payment.handler")}}',
                        successCallBack: function (response) {
                            // Ladda.stopAll();

                            // console.log(response);
                            if (response['result']['next'] == 'pending') {
                                $('#payment_result').html(response['result']['content']);
                                $('.prompt-link[data-target="pay-address"]').attr('type','button');
                                $('.visible-xs-block').closest('.col-sm-9').removeClass('.col-sm-9');
                                $('.hidden-xs').remove();
                                $('.visible-xs-block').remove();
                                $('#tab6').modal('show');
                                $('.pay-now-btn').addClass('b2bpay');
                                $('.pay-now-btn').attr('href',response['result']['url']);
                                $('.pay-now-btn').hide();

                                $.ajax({
                                    url:'{{route("b2b.success")}}',
                                    method:"POST",
                                    data:{address:$('.prompt-link[data-target="pay-address"]').html()},
                                    success:function(res){
                                        $('#formular_content_content').html(res);
                                        $('#formular_content').modal('show');
                                    }
                                })
                            }
                        },
                        failCallBack: function (response) {
                            console.log(response.responseJSON.errors)
                            for(let item in response.responseJSON.errors) {
                                switch (item) {
                                    case "sponsor":
                                        toastr['error']("Please Enter Sponsor!");
                                        break;
                                    case "username":
                                        toastr['error']("Please Enter Username!");
                                        break;
                                    case "password":
                                        toastr['error']("Please Enter Password!");
                                        toastr['error']("Please Enter Confirm Password!");
                                        break;
                                    case "email":
                                        toastr['error']("Please Enter Email!");
                                        break;
                                    case "phone":
                                        toastr['error']("Please enter Mobile Number!");
                                        break;
                                    case "firstname":
                                        toastr['error']("Please Enter Given Name!");
                                        break;
                                    case "lastname":
                                        toastr['error']("Please Enter Surname!");
                                        break;
                                    case "dob":
                                        toastr['error']("Please Enter Date of birth!");
                                        break;
                                    case "passport_no":
                                        toastr['error']("Please Enter Passport or National ID Number!");
                                        break;
                                    case "date_of_passport_issuance":
                                        toastr['error']("Please Enter date of Passport Issuance!");
                                        break;
                                    case "passport_expirition_date":
                                        toastr['error']("Please Enter Passport Expirition Date!");
                                        break;
                                    case "street_name":
                                        toastr['error']("Please Enter Street Name!");
                                        break;
                                    case "house_no":
                                        toastr['error']("Please Enter House Number!");
                                        break;
                                    default:
                                        toastr['error']("Error!!!");
                                        break;
                                }
                            }
                            // Ladda.stopAll();
                        }
                    };
                }
                else
                {
                     options = {
                        actionUrl:'{{route(getScope() . ".payment.handler")}}',
                        gateway:gateway,
                        payment:payment,
                        successCallBack: function (response) {
                            // console.log(response)
                            // Ladda.stopAll();
                            if(gateway == 'Payment-Freejoin')
                            {
                                if (window.hasOwnProperty('paymentSuccessCallback')) {
                                    window.paymentSuccessCallback(response, 'freejoin');
                                } 
                            }
                            else if(gateway == 'Payment-SafeCharge')
                            {
                                var form = document.createElement("form");
                                form.method = "POST";
                                form.action = "{{route('SafeCharge.payment')}}";
                                var string = response.result.original?response.result.original.message:response.result.message;
                                response = JSON.parse(string);
                                for(item in response)
                                {
                                      const hiddenField = document.createElement('input');
                                      hiddenField.type = 'hidden';
                                      hiddenField.name = item;
                                      hiddenField.value = response[item];
                                      form.appendChild(hiddenField);
                                }

                                document.body.appendChild(form);
                                form.submit();   
                            }
                            else if(gateway == 'Payment-TransferWise')
                            {
                                if(response.result.message || response.result.original.message)
                                {
                                    var username = response.result.message?response.result.message:response.result.original.message;
                                    var form = document.createElement('form');

                                    form.method = 'POST'; 
                                    form.action = '{{route("transferwise.success")}}';

                                    var input_name = document.createElement('input');
                                    input_name.type = 'hidden';
                                    input_name.name = "username";
                                    input_name.value = username;
                                    form.appendChild(input_name);

                                    var input_payment = document.createElement('input');
                                    input_payment.type = 'hidden';
                                    input_payment.name = "payment";
                                    input_payment.value = payment;
                                    form.appendChild(input_payment);
                                    document.body.appendChild(form);
                                    form.submit(); 
                                }
                            }
                            
                        },
                        failCallBack: function (response) {
                            console.log(response.responseJSON.errors)
                            for(let item in response.responseJSON.errors) {
                                switch (item) {
                                    case "sponsor":
                                        toastr['error']("Please Enter Sponsor!");
                                        break;
                                    case "username":
                                        toastr['error']("Please Enter Username!");
                                        break;
                                    case "password":
                                        toastr['error']("Please Enter Password!");
                                        toastr['error']("Please Enter Confirm Password!");
                                        break;
                                    case "email":
                                        toastr['error']("Please Enter Email!");
                                        break;
                                    case "phone":
                                        toastr['error']("Please enter Mobile Number!");
                                        break;
                                    case "firstname":
                                        toastr['error']("Please Enter Given Name!");
                                        break;
                                    case "lastname":
                                        toastr['error']("Please Enter Surname!");
                                        break;
                                    case "dob":
                                        toastr['error']("Please Enter Date of birth!");
                                        break;
                                    case "passport_no":
                                        toastr['error']("Please Enter Passport or National ID Number!");
                                        break;
                                    case "date_of_passport_issuance":
                                        toastr['error']("Please Enter date of Passport Issuance!");
                                        break;
                                    case "passport_expirition_date":
                                        toastr['error']("Please Enter Passport Expirition Date!");
                                        break;
                                    case "street_name":
                                        toastr['error']("Please Enter Street Name!");
                                        break;
                                    case "house_no":
                                        toastr['error']("Please Enter House Number!");
                                        break;
                                    default:
                                        toastr['error']("Error!!!");
                                        break;
                                }
                            }
                            
                            // Ladda.stopAll();
                        }
                    }
                }
                sendForm(options);
            })

            return false;
        })        
    </script>
@endsection