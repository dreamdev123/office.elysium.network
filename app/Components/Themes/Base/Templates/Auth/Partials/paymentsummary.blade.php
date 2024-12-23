<div class="paycontainer" style="margin-top: 30px;">
    <div class="row" style="display: flex;align-items: center;">
        <div class="col-sm-4" style="margin-top: -40px;">
            <img src="{{asset('images/ElysiumLogo.png')}}" class="logo"/>
        </div>
        <div class="col-sm-8">
            <h4 class="logocolor" style="font-size: ">{{currency($total)}} First Order Payment (One Time)</h4>
        </div>
    </div>
    <div class="row" style="display: flex; align-items: center; margin-top: 10px;">
        <div class="col-sm-4" style="margin-top: -45px;">
            <img src="{{asset('images/CardsBlack.png')}}" class="logo"/>
        </div>
        <div class="col-sm-8">
            <div class="row" style="display: flex;margin-top: 20px;">
               <!--  <div class="col-sm-4">
                   <span class="btn btn-payment" attr_type="Payment-SafeCharge" style="margin-left: auto;height:43px;display: flex;width: 100%;  align-items: center;justify-content: center;">Safe Charge</span>  
                </div> -->
                <?php if(isAdmin()){?>
                    <div class="col-sm-3">
                       <span class="btn btn-payment" attr_type="Payment-Freejoin" style="margin-left: auto;height:43px;display: flex;width: 100%;  align-items: center;justify-content: center;">Free Pay</span> 
                    </div>    
                <?php }?>
                <div class="col-sm-<?php echo isAdmin()?3:4;?>">
                   <span class="btn btn-payment" attr_type="Payment-SafeCharge" style="margin-left: auto;height:43px;display: flex;width: 100%;  align-items: center;justify-content: center;">CARD</span> 
                </div>
                {{--<div class="col-sm-<?php echo isAdmin()?3:4;?>">
                   <span class="btn btn-payment" attr_type="Payment-TransferWise" style="margin-left: auto;height:43px;display: flex;width: 100%;  align-items: center;justify-content: center;">IBAN</span> 
                </div>--}}
                {{--<div class="col-sm-<?php echo isAdmin()?3:4;?>">
                    <span class="btn btn-payment" attr_type="Payment-B2BPay" style="width: 100%;justify-content: center;height: 45px; align-items: center;display: flex;">
                    <img src="{{asset('images/payment/Bitcoin.png')}}" style="width:50px;position: absolute;left: 0;"/>BITCOIN</span> 
                </div>--}} 
                

            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-lg-12" style="display: flex;align-items: center;">
                     <div class="radio-list" style="width: 30px;height: 30px">
                        <label class="radio-container">
                            <input type="checkbox" name="agreement" value="agree"
                                   data-title="Male"/>
                            <span class="checkbox-circle" style="margin-top: 6px;"></span>
                        </label>
                    </div>
                    <p class="footer_desc">I agree to the <a href="javascript:;" style="color: black;" data-toggle="modal" data-target="#terms">Terms and Conditions</a> and the Refund and <a href="javascript:;" style="color: black;" target="blank">Cancellation Policy </a> with hyperlinks to the related documents<p>
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
    <input type="hidden" name="payment" value="IBAN"/>

    
    <!-- <div class="row content_payment">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="row">
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/deal.png')}}" class="payicon">    
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="deal"
                                   data-title="Male"
                                   checked="checked"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/visa.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="visa"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/paypal.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="paypal"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/klarna.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="klarna"
                                   data-title="Male"
                                   checked="checked"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/sofort.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="sofort"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/Bancontact.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="Bancontact"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/giropay.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="giropay"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/transferwise.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment" value="transferwise"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row" style="display: flex;">
               <span class="btn btn-payment" attr_type="Payment-TransferWise" style="margin-left: auto;">IBAN PAYMENT</span> 
                <span class="btn btn-payment" attr_type="Payment-B2BPay" style="margin-left: 30px;margin-right: auto;">B2B PAYMENT</span> 
            </div>
        </div>
    </div> -->
</div>

@if($monthly)
<!-- <div class="paycontainer" style="margin-top: 20px;">
    <div class="row" style="display: flex;align-items: center;">
        <div class="col-sm-4">
            <img src="{{asset('images/ElysiumLogo.png')}}" class="logo"/>
        </div>
        <div class="col-sm-7 col-lg-offset-1">
            <h4 class="logocolor" style="font-size: ">{{currency(79.95)}} MONTHLY SUBSCRIPTION PAYMENT </h4>
            <h4 class="logocolor">(STARTS AFTER 30 DAYS)</h4>
        </div>
    </div>
    <div class="row content_payment">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="row">
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/visa.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment_monthly" value="visa"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/paypal.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment_monthly" value="paypal"
                                   data-title="Male"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-3" style="text-align: center;">
                    <div class="payment-item">
                        <img src="{{asset('images/payment/klarna.png')}}" class="payicon">
                    </div>
                    <div class="radio-list">
                        <label class="radio-container">
                            <input type="radio" name="payment_monthly" value="klarna"
                                   data-title="Male"
                                   checked="checked"/>
                            <span class="checkbox-circle"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endif
<input type="hidden" name="gateway"/>
<!-- <div class="row" style="margin-top: 20px;">
    <div class="col-lg-8 col-lg-offset-2" style="display: flex;align-items: center;">
         <div class="radio-list" style="width: 30px;height: 30px">
            <label class="radio-container">
                <input type="checkbox" name="agreement" value="agree"
                       data-title="Male"/>
                <span class="checkbox-circle"></span>
            </label>
        </div>
        <p class="footer_desc">I agree to the <a href="https://www.elysiumnetwork.io/legal/terms-of-use" style="color: black;" target="blank">Terms and Conditions</a> and the Refund and <a href="https://www.elysiumnetwork.io/legal/terms-of-supply" style="color: black;" target="blank">Cancellation Policy </a> with hyperlinks to the related documents<p>
    </div>
</div> -->
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
    $('input[name=agreement]').change(function(){
        let checked = this.checked;
        $('.proceed').attr('disabled',!checked);
    })
    $('.btn-payment').click(function(){
        $('.btn-payment').each(function(){
            $(this).removeClass('btn-payment-selected');
        })

        $(this).addClass('btn-payment-selected');

        if($('.proceed').hasClass('package_upgrade'))
        {
            // if ($(this).attr('attr_type') == 'Payment-SafeCharge') {
            //     $('#safecharge-error').modal('show');
            // } else {
            //     $('#upgrade_content').modal('show');
            // }
            $('#upgrade_content').modal('show');
        } else {
            // if ($(this).attr('attr_type') == 'Payment-SafeCharge') {
            //     $('#safecharge-error').modal('show');
            // } else {
            //     $('#iban-alert').modal('show');
            // }
            if ($(this).attr('attr_type') != 'Payment-Freejoin') {
                $('#iban-alert').modal('show');
            }
        }
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

    // $('#safecharge').unbind('click').click(function(){
    //     $.get("{{route(getScope() . '.getGatewayitem')}}",{context:$('input[name=context]').val(),gateway:'Payment-SafeCharge'}).then(res=>{
    //         $('input[name=gateway]').val(res.id);
    //         let options = {
    //                 actionUrl:'{{route(getScope() . ".payment.handler")}}',
    //                 gateway:'Payment-SafeCharge',
    //                 payment:'visacard',
    //                 successCallBack: function (response) {
    //                     var form = document.createElement("form");
    //                     form.method = "POST";
    //                     form.action = "{{route('SafeCharge.payment')}}";
    //                     response = JSON.parse(response.result.message);
    //                     for(item in response)
    //                     {
    //                           const hiddenField = document.createElement('input');
    //                           hiddenField.type = 'hidden';
    //                           hiddenField.name = item;
    //                           hiddenField.value = response[item];
    //                           form.appendChild(hiddenField);
    //                     }

    //                     document.body.appendChild(form);
    //                     form.submit();   
    //                 }
    //             }
    //          sendForm(options);
    //     })
    // })

    $('.proceed').unbind('click').click(function(){

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
        //gateway = 'Payment-SafeCharge';
        var payment  = "";


        if($('.btn-payment-selected').html() == 'IBAN PAYMENT')
        {
            payment = 'IBAN';
        }
        else if($('.btn-payment-selected').html() == 'SWIFT PAYMENT')
        {
            payment = "SWIFT";
        }
        // $('input[name=payment]').each(function (){
        //     if(this.checked)
        //     {
        //         payment = $(this).val();
        //     }
        // })

        // var gateway =  'Payment-SafeCharge';
        // if(payment == 'transferwise')
        // {
        //     gateway = 'Payment-TransferWise';
        // }
        // if(payment == 'visa')
        // {
        //     gateway = "Payment-ZotaPay";
        // }
        // else if(payment == 'paypal')
        // {
        //     gateway = 'Payment-SafeCharge';
        // }

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
                        Ladda.stopAll();

                        console.log(response);
                        if (response['result']['next'] == 'pending') {
                            //window.location.replace(response['result']['redirectUri']);
                            $('#payment_result').html(response['result']['content']);
                            $('.prompt-link[data-target="pay-address"]').attr('type','button');
                            $('.visible-xs-block').closest('.col-sm-9').removeClass('.col-sm-9');
                            $('.hidden-xs').remove();
                            $('.visible-xs-block').remove();
                            $('#tab6').addClass('active');
                            $('#tab5').removeClass('active');
                            $('.proceed').addClass('b2bpay');
                            $('.proceed').attr('href',response['result']['url']);
                            $('.proceed').hide();

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
                        Ladda.stopAll();
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
                        Ladda.stopAll();
                        if(gateway == 'Payment-Zota')
                        {
                            if (response['result']['next'] == 'pending') {
                                window.location.replace(response['result']['redirectUri']);
                            }    
                        }
                        else if(gateway == 'Payment-Freejoin')
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
                        else
                        {
                            var form = document.createElement("form");
                            form.method = "POST";
                            form.action = "{{route('Genome.payment')}}";
                            response = JSON.parse(response.result.message);
                            response['paymentmethod'] = payment;
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
                        
                    },
                    failCallBack: function (response) {
                        Ladda.stopAll();
                    }
                }
            }

            sendForm(options);
        })

        return false;

    })

    
</script>

<style type="text/css">
    .payicon
    {
        width: 70%;
    }

    .paycontainer .logocolor
    {
        font-size: 16px;
    }

    .paycontainer .logo{
        width: 90% !important;
    }
    .paycontainer .proceed
    {
        background-color: #08b790;
        border-color: #08b790;
        width: 50%;
    }

    .enter
    {
        background-color: #08b790;
        border-color: #08b790;   
        width: 50%;
    }

    .wow .mb-4 img{
        height: 35px !important;
        margin-top: 6px;
    }
    .wow .mb-4{
        display: flex;
    }

    .payment-item
    {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        height:50px;
        width: 100%;
        justify-content: center;
    }

    .content_payment
    {
        margin: 50px 20px 0 20px;
        background-color: white;
        padding:20px;
        justify-content: center;
    }

    .paycontainer .radio-list
    {
        width: 100%;
        height:30px;
        /*display: unset;*/
    }

    .footer_row
    {
        margin-top: 3px;
    }

    .footer_row .info img
    {
        width:45px;
        vertical-align: middle;
        display: inline-block;
    }

    .info
    {
        margin-top:20px;
    }
    .footer_desc
    {
        margin:0px 30px;
        display: inline-block;
        vertical-align: middle;
        color:grey;
        font-size: 14px;
    }

    .btn-payment
    {
        background-color: black;
        color: white;
        margin:auto;
        cursor:pointer;
        width: 50%;
        height: 50px;
    }

    .btn-payment-selected
    {
        background-color: grey;
    }

    .btn-payment:hover
    {
        background-color: grey;
    }
</style>