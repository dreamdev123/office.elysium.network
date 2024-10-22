 @extends('User.Layout.master')
    @section('content')
    @include('_includes.profile_nav')
    <link href="{{ asset('css/user/css/dashboard_fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user/css/style.css') }}" rel="stylesheet">
    <style type="text/css">
            
        .info-subtitle {
            font-family: 'DIN Pro Condensed Regular', sans-serif; 
            font-size: 18px; 
            color: #41464d;
            text-transform: uppercase;
            font-weight: 400;
        }
        .button-submit {
            width: 100%;
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

        .pay-now-btn {
            font-family: 'DIN Pro Condensed Bold', sans-serif; 
            color: #FFFFFF !important;
            background-color: #d22630 !important;
            border: none !important;
            border-radius: 0;
            text-transform: uppercase;
            width: 100%;
        }

        .btn-payment-selected {
            background-color: #41464d !important;
        }

        .license-section1 {
            border-right: 2px solid #a6a8ab;
        }
        .title-active {
            color: #37b34a;
            font-weight: 700;
        }
    </style>
    <?php
        $user = loggedUser(); 
        $discountStatus = false;
        $subcription_days_7 = false; $insider_days_7 = false; $expire = false;
        if($user)
        {
            $numExpiryDates = 0;
            $numInsiderExpiryDates = 0;

            if($user->expiry_date && $user->expiry_date != '0000-00-00')
            {
                $expiry_date = $user->expiry_date;
                $today = date('Y-m-d');
                if(strtotime($expiry_date) > strtotime($today))
                {
                    $numExpiryDates = range(strtotime($today), strtotime($expiry_date),86400);
                    $numExpiryDates = count($numExpiryDates);
                } else {
                    $numExpiryDates = 0;
                }
            } elseif (!isset($user->expiry_date)) {
                $numExpiryDates = 0;
            }

            if($numExpiryDates < 8)
            {
                $subcription_days_7 = true;   
            }

            if ($user->package->slug == 'affiliate') {
                $expire = true;
            } elseif ($subcription_days_7) {
                $expire = true;
            }
        }

    ?>
    @if($user->package->slug == 'affiliate' || $user->package->slug == 'client')
    <div class="row">
        <div class="col-sm-12" style="margin-top: 30px;">
            <h4>Sorry. This option is not available for Affiliate.  In order to use this feature, you need to upgrade to an IB or the above.</h4>
            <a href="{{ scopeRoute('packageUpgrade') }}" class="btn" style="color: #fff; background-color: #32c5d2;"> <span class="upgradeEarning"><i class="fa fa-plus-circle" style="margin-right: 3px"></i>UPGRADE</span> </a>
        </div>
    </div>
    @else
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-10 bg-white">
            <div class="row">
                @if ($user->package->slug != 'affiliate' || $user->package->slug != 'client')
                <div class="col-lg-6 col-md-6 ">
                    <div class="d-flexw-100" style="padding: 3rem;">
                        <div class="info-subtitle"><b>Subscription membership</b></div>
                    </div>
                    @php
                    $today = date('2021-08-07');
                    $lastDay = date($user->created_at);
                    if (strtotime($lastDay) < strtotime($today)) {
                        $discountStatus = true;
                    }
                    @endphp
                    <div class="w-100 license-section1 bg-white pr-5 pl-5">
                        <div class="d-flex w-100">
                            <div style="width: 65%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="membership0" >
                                    <span class="checkmark1 mr-1"></span>
                                    <span class="info-subtitle">MEMBERSHIP 1 MONTH</span>
                                </label>
                            </div>
                            <div class="info-subtitle" style="width: 15%;"> </div>
                            <div class="info-subtitle" style="width: 20%;">EURO <?php echo $discountStatus == true ? '79.95' : '97,00'?></div>
                        </div>
                        <div class="d-flex w-100">
                            <div style="width: 65%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="membership1" >
                                    <span class="checkmark1 mr-1"></span>
                                    <span class="info-subtitle">MEMBERSHIP 3 MONTHS</span>
                                </label>
                            </div>
                            <div class="info-subtitle" style="width: 15%;"> </div>
                            <div class="info-subtitle" style="width: 20%;">EURO 240,00</div>
                        </div>
                        <!-- <div class="d-flex w-100">
                            <div style="width: 65%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="membership2" >
                                    <span class="checkmark1 mr-1"></span>
                                    <span class="info-subtitle">MEMBERSHIP 6 MONTHS</span>
                                </label>
                            </div>
                            <div class="info-subtitle" style="width: 15%; color: #37b34a;"><b>-5%</b></div>
                            <div class="info-subtitle" style="width: 20%;">EURO 456,00</div>
                        </div> -->
                        <div class="d-flex pb-3 w-100">
                            <div style="width: 65%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_membership" class="radio-button subscriptionRadio" value="membership3" >
                                    <span class="checkmark1 mr-1"></span>
                                    <span class="info-subtitle">MEMBERSHIP 12 MONTHS</span>
                                </label>
                            </div>
                            <div class="info-subtitle" style="width: 15%; color: #37b34a;">{{--<b>-10%</b>--}}</div>
                            <div class="info-subtitle" style="width: 20%;">EURO 880,00</div>
                        </div>
                    </div>
                </div>
                @endif
                {{--<div class="col-lg-6 col-md-6 @if ($user->package->slug != 'affiliate') pl-0 @endif">
                    <div class="d-flex w-100" style="padding: 3rem;">
                        <div class="info-subtitle"><b>INSIDER RESEARCH & ANALYSIS - SIGNAL REPORTS</b></div>
                    </div>
                    <div class="w-100  license-section2 bg-white pr-5 pl-5">
                        @if ($user->package->slug != 'affiliate')
                        <div class="d-flex w-100" style="opacity: 0;">
                            <div style="width: 80%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadi" value="insider0" >
                                    <span class="checkmark1 mr-1"></span>
                                    <span class="info-subtitle">MEMBERSHIP 1 MONTHS</span>
                                </label>
                            </div>
                            <div class="info-subtitle ibInsiderMembership0" style="width: 20%;">EURO 79.95</div>
                            <div class="info-subtitle ibInsiderMembership0_1 title-active" style="width: 20%; display: none;">FREE</div>
                        </div>
                        @endif
                        <div class="d-flex w-100">
                            <div style="width: 80%;">
                                <label class="checkbox-container">
                                    <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="insider1" >
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
                                    <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="insider2" >
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
                                    <input type="radio" name="package_insider_membership" class="radio-button ib-insiderRadio" value="insider3" >
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
                    </div>
                </div>--}}
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="w-100 bg-white ib-total-price" style="padding: 3rem;">
                        <div class="d-flex mb-3 w-100">
                            <div class="info-subtitle" style="width: 80%;"><b>ORDER TOTAL</b></div>
                            <div class="info-subtitle"><b class="total-price">EURO 0,00</b></div>
                            <input type="hidden" name="price" value="0" />
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
            </div>
            <form class="row pt-5 pb-3 pr-5 pl-5">
                <div class="col-lg-4 col-md-4 p-3" style="display: flex; align-items: center;">
                    <img src="{{asset('images/LogoCards.png')}}" class="logo w-100"/>
                </div>
                <div class="col-lg-7 col-md-8 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 p-3">
                            <span class="btn btn-success btn-block btn-lg payment-btn" attr_type="Payment-SafeCharge">CARD</span>
                        </div>
                        <!-- <div class="col-lg-4 col-md-4 col-sm-4 p-3">
                            <span class="btn btn-success btn-block btn-lg payment-btn" attr_type="Payment-TransferWise">BANK</span>
                        </div> -->
                        <div class="col-lg-4 col-md-4 col-sm-4 p-3">
                            <button class="btn btn-success btn-block btn-lg pay-now-btn" disabled>Pay Now</button>
                        </div>
                        <input type="hidden" name="gateway" />
                        <input type="hidden" name="amount" />
                        <input type="hidden" name="context" value="subscription" />
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-12" style="margin-top: 30px; font-family: 'Open Sans' !important;">

            <h4>Subscription Expiration Date : {{$user->expiry_date}}</h4>

            <h4>Insider Expiration Date : {{ $user->insider_expiry_date }}</h4>

            <p class="mt-2" style="font-family: 'Open Sans' !important;">For enquiries regarding your Subscription, kindly write to support at <span class="text-primary">support@elysiumnetwork.io</span></p>

            <p class="mt-2" style="font-family: 'Open Sans' !important;"><span style="color: red">* </span> If your card is about to expire, or would like to change your current subscription, please contact support to choose a different form of payment.</p>

        </div>
    </div>
    @endif
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.subscriptionRadio').prop('checked', false);
            $('.ib-insiderRadio').prop('checked', false);
            $.get('{{route("transferwise.countryfilter")}}',{country: {{$user->metaData->country_id}} },function(res){
                res = JSON.parse(res);
                if(res.success)
                {
                    $('.payment-btn[attr_type=Payment-TransferWise]').html('IBAN');
                }   
                else
                {
                    $('.payment-btn[attr_type=Payment-TransferWise]').html('SWIFT');
                }
            })
        });

        $('.subscriptionRadio').change(function(){
            if( $(this).is(":checked") ) {
                $('.ibInsiderMembership1').show();
                $('.ibInsiderMembership1_1').hide();
                $('.ibInsiderMembership2').show();
                $('.ibInsiderMembership2_1').hide();
                $('.ibInsiderMembership3').show();
                $('.ibInsiderMembership3_1').hide(); 
                var total_price = '';
                if ($(this).val() == 'membership0') {
                    $('input[name=price]').val(<?php echo $discountStatus == true ? '79.95' : '97'?>);
                    total_price = 'EURO <?php echo $discountStatus == true ? '79.95' : '97,00'?>';
                    // $('.ibInsiderMembership1').hide();
                    // $('.ibInsiderMembership1_1').show();
                } else if ($(this).val() == 'membership1') {
                    $('input[name=price]').val(240);
                    total_price = 'EURO 240,00';
                    $('.ibInsiderMembership1').hide();
                    $('.ibInsiderMembership1_1').show();
                    $("input[name=package_insider_membership][value=" + 'insider1' + "]").prop('checked', true);
                } else if ($(this).val() == 'membership2') {
                    $('input[name=price]').val(456);
                    total_price = 'EURO 456,00';
                    $('.ibInsiderMembership2').hide();
                    $('.ibInsiderMembership2_1').show();
                    $("input[name=package_insider_membership][value=" + 'insider2' + "]").prop('checked', true);
                } else if ($(this).val() == 'membership3') {
                    $('input[name=price]').val(880);
                    total_price = 'EURO 880,00';
                    $('.ibInsiderMembership3').hide();
                    $('.ibInsiderMembership3_1').show();
                    $("input[name=package_insider_membership][value=" + 'insider3' + "]").prop('checked', true);
                }
                $('.ib-total-price').find('.total-price').eq(0).html(total_price);
            }
            activePayNowBTN();
        });

        $('.ib-insiderRadio').change(function(){
            if( $(this).is(":checked") ) {
                $('.ibInsiderMembership1').show();
                $('.ibInsiderMembership1_1').hide();
                $('.ibInsiderMembership2').show();
                $('.ibInsiderMembership2_1').hide();
                $('.ibInsiderMembership3').show();
                $('.ibInsiderMembership3_1').hide();
                $('.subscriptionRadio').prop('checked', false);
                var total_price = '';
                if ($(this).val() == 'insider1') {
                    $('input[name=price]').val(199);
                    total_price = 'EURO 199,00';
                } else if ($(this).val() == 'insider2') {
                    $('input[name=price]').val(299);
                    total_price = 'EURO 299,00';
                } else if ($(this).val() == 'insider3') {
                    $('input[name=price]').val(499);
                    total_price = 'EURO 499,00';
                }
                $('.ib-total-price').find('.total-price').eq(0).html(total_price);
            }
            activePayNowBTN();
        });

        function activePayNowBTN() {
            let selectedMembership = $('input[name=price]').val();
            if (selectedMembership > 0 && $('.payment-btn').hasClass('btn-payment-selected')) {
                $('.pay-now-btn').attr('disabled', false);
            } else {
                $('.pay-now-btn').attr('disabled', true);
            }
        }

        $('.payment-btn').click(function(){
            $('.payment-btn').each(function(){
                $(this).removeClass('btn-payment-selected');
            })
            if ($(this).attr('attr_type') == 'Payment-TransferWise') {
                $('#iban-alert').modal('show');
            }
            $(this).addClass('btn-payment-selected');
            activePayNowBTN();
        })

        window.targetForm = $('form');
        $('.pay-now-btn').click(function(){
            var price = $('input[name=price]').val()
            var gateway = $('.btn-payment-selected').attr('attr_type');
            var payment  = "IBAN";
            if($('.btn-payment-selected').html() == 'IBAN')
            {
                payment = 'IBAN';
            }
            else if($('.btn-payment-selected').html() == 'SWIFT')
            {
                payment = "SWIFT";
            }

            $.get("{{route(getScope() . '.getGatewayitem')}}",{context:$('input[name=context]').val(),gateway:gateway}).then(res=>{
                $('input[name=gateway]').val(res.id);
                $('input[name=amount]').val(price);
                
                var options = {};

                options = {
                    actionUrl:'{{route(getScope() . ".payment.handler")}}',
                    gateway: gateway,
                    payment: payment,
                    amount: price,
                    successCallBack: function (response) {
                        Ladda.stopAll();
                        if(gateway == 'Payment-SafeCharge')
                        {
                            var form = document.createElement("form");
                            form.method = "POST";
                            form.action = "{{route('SafeCharge.payment')}}";
                            response = JSON.parse(response.result.message);
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
                        Ladda.stopAll();
                    }
                }

                sendForm(options);
            })

            return false;

        })
    </script>

    @endsection



