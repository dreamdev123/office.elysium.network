<?php 
    use App\Eloquents\Package;
    use Illuminate\Support\Facades\Route;
    use App\Blueprint\Services\ExternalMailServices;
    use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
    use App\Components\Modules\Payment\TransferWise\ModuleCore\Eloquents\TransferWiseTransaction;
    use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
?>
@php
    $user = loggedUser(); 
    $expires = false; $days_7 = false; $unpaiduser = false;
    if($user)
    {
        $package = Package::find($user->package_id);

        $valid_url = array("user.getGatewayitem","user.payment.handler","user.logout","user.getGateways","user.payment.callback");

        if($user->expiry_date && $user->expiry_date != '0000-00-00' && !in_array(Route::currentRouteName(),$valid_url))
        {
            if(Route::currentRouteName() != 'user.expirepayment')
            {
                $expiry_date = $user->expiry_date;
                $today = date('Y-m-d');


                if( !$expiry_date || strtotime($expiry_date) < strtotime($today) )
                {
                    $expires = true;

                    $transfer  = TransferWiseTransaction::where("reference", $user->customer_id)->where('context', 'Registration')->first();
                    $b2btransaction = B2BPayTransaction::where('reference_id', $user->customer_id)->where('context', 'Registration')->first();
                    if (isset($transfer) || isset($b2btransaction)) {
                        $unpaiduser = true;
                    }
                }
            }
        } elseif (!isset($user->expiry_date) && $user->package->slug != 'affiliate' && $user->package->slug != 'client') {
            $unpaiduser = true;
            $user->update([
                'expiry_date'=>date('Y-m-d'),
                'updated_at'=>date('Y-m-d')
            ]);
        }

        $numExpiryDates = 0;

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

        if($numExpiryDates < 8 && $user->package->slug != 'affiliate' && $user->package->slug != 'client')
        {
            $date_now = new \DateTime('now');
            $updated_at = new \DateTime($user->updated_at);

            if($date_now->getTimestamp() > $updated_at->getTimestamp())
            {
                $safechargesubscription = SafechargeSubscription::where('user_id',$user['id'])->first();
                if(!$safechargesubscription)
                {
                    $days_7 = true;   
                }
                $user->update([
                    'updated_at'=>date('Y-m-d 23:59:59')
                ]);
            }
        }
    }
   
    @endphp
@include('User.Layout.header')

<!-- BEGIN HEADER CLEARFIX-->
<div class="clearfix"></div>

@if(!$unpaiduser && $expires)
     <div class="modal fade in show" id="formular">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-body">
                <div class="alert alert-block alert-danger">
                    <h4>Attention !</h4>
                    
                    Hi there. Your account has been temporarily disabled for login.<br>
                    Kindly proceed to make payment for your expired subscription, and regain access'.<br><br>
                    PLEASE CLICK THE BUTTON BELOW to go to subscriptions page to manage your payment status<br>

                    <div class="row" style="text-align: center;">
                        <a href="{{route('user.expirepayment')}}" class="btn btn-primary">Go To Subscription</a>
                        <!-- <a href="javascript:;" class="btn btn-primary show-subscription-modal">Go To Subscription</a> -->
                    </div><br>

                    (PLEASE CLICK THE ABOVE BUTTON FOR SUBSCRIPTION PAYMENT)<br><br>
                    <span style="color: black">However, If you would like to downgrade to an Affiliate position and get instant access to your account instead, please email: <a href="mailto:support@elysiumnetwork.io">support@elysiumnetwork.io,</a> and we will get on it right away.</span><br>
                </div>
            </div>
          </div>
        </div>
    </div>  
@endif

@if($unpaiduser)
     <div class="modal fade in show" id="formular">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-body">
                <div class="alert alert-block alert-danger">
                  <h4>Attention !</h4>
                    
                    Hi there. Your account has been temporarily disabled for login.<br>
                    Kindly proceed to make payment for your account package, to regain access.<br><br>
                    <span style="color: black">Bank code: (IBAN / BIC): TRWIBEB1XXX</span><br>
                    <span style="color: black">IBAN: BE21 9670 5753 4403</span><br>
                    <span style="color: black">REFERENCE: {{$user->customer_id}}(your reference ID which we sent to your email, when your account was registered)</span><br><br>

                    <span style="color: black">For enquiries, please email: <a href="https://www.elysiumnetwork.io/">support@elysiumnetwork.io.</a></span>
                </div>
            </div>
          </div>
        </div>
    </div>  
@endif

@if($days_7)
     <div class="modal" id="days_7">

        <div class="modal-dialog" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                @include('User.Settings.days7',['user'=>$user])
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>  
@endif

    <div class="modal" id="re-instate">

        <div class="modal-dialog" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                Hi, if you have previously Downgraded from an IB to Affiliate, and would like to re-instate yourself to be an IB again, please contact <a href="mailto:support@elysiumnetwork.io">support@elysiumnetwork.io.</a> and we will get on it right away. 

            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div> 

    <div class="modal" id="subscription-alert">

        <div class="modal-dialog modal-dialog-centered" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                <p>We kindly ask for your patience whilst the subscription and renewal options are being updated.</p>

                <p>We expect this function to be available for you by the 1st of April.</p>

                <p>Thank you for your patience.</p>
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div> 

    <div class="modal" id="facebook-alert">

        <div class="modal-dialog modal-dialog-centered" style="width: 650px;">

          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <div class="modal-body">
                <p>This option will be available soon. Thank you for your patience!</p>
            </div>
              <div class="modal-footer">          
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div> 
<div class="page-container">
@include('User.Layout.sideBar')
<!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content" style="min-height: 1001px;">
            <div class="row mainBreadcrumb">
                <div class="col-sm-12">
                    <div class="col-sm-5 BreadcrumbLeft">
                        @section('pageTitle')
                            <h1 class="page-title"> @if(isset($heading_text)){{ $heading_text }}@endif</h1>
                        @show
                        @if(isset($breadcrumbs))
                            <div class="page-bar">
                                <ul class="page-breadcrumb">
                                    @foreach($breadcrumbs as $key=> $value)
                                        <li>
                                            <a href="@if(isset($value)  && $value != '#'){{  route($value) }}@endif">{{ $key }}</a>

                                            @if($loop->iteration!=count($breadcrumbs))
                                                <i class="fa fa-angle-right"></i>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-7 BreadcrumbRight">
                        <div class="pageTitleRight">
                            {!! defineFilter('pageTitleRight', '', 'header'); !!}
                        </div>
                    </div>
                </div>
            </div>
            @section('notification')
            @show
            @yield('content')
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    @include('User.Layout.quickSideBar')
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#starter').modal('show');
        $('button[data-dismiss=modal]').click(function(){
            $('#starter').modal('hide');
        })    

        if($('#days_7').length > 0)
        {
            $('#days_7').modal('show');
        }
        $('.re-instate').click(function() {
            $('#re-instate').modal('show');
        })
        $('.show-subscription-modal').click(function() {
            $('#subscription-alert').modal('show');
        })
        $('.facebook-modal').click(function() {
            $('#facebook-alert').modal('show');
        })
    })
    
</script>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
@include('User.Layout.footer')
<!-- END FOOTER -->