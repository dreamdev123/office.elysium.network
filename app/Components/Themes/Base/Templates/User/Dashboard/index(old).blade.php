@extends('User.Layout.master')
@section('content')
    <div class="row personalInfoGridWrapper">
        {{--<div class="col-sm-2">
            <div class="infoGrid">
                <h3>TVC Cycles</h3>
                <h5>{{ $cycle }}</h5>
            </div>
        </div>--}}
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>Member ID</h3>
                <h5>{{ loggedUser()->customer_id }}</h5>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>Name</h3>
                <h5>{{ loggedUser()->metaData->firstname }} {{ loggedUser()->metaData->lastname }}</h5>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>CURRENT RANK</h3>
                <h5>{{ $currentRank && !empty($currentRank->rank) ? $currentRank->rank->name : 'No Rank' }}</h5>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>HIGHEST RANK</h3>
                <h5>{{ $highestRank && !empty($highestRank->rank) ? $highestRank->rank->name : 'No Rank'  }}</h5>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>PACKAGE</h3>
                <h5>{{ $package ? $package->name : 'No package' }}</h5>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="infoGrid">
                <h3>STATUS</h3>
                <h5>@if($active && ($active->extended_date < $today)) Active @else Inactive @endif</h5>
            </div>
        </div>
        {{--<div class="col-sm-2">
            <div class="infoGrid">
                <h3>EARNINGS</h3>
                <h5 class="amount"><span>€</span>{{ $commissionTotal }}</h5>
            </div>
        </div>--}}
    </div>

    <div class="row">
        <div class="col-sm-12">

            <div class="row careerWrapper" style="margin-top: 10px;">
                <div class="col-sm-7">
                    <br/>
                    <p onclick="copyLink(this,event)" attr_href="https://www.elysiumnetwork.io/{{ loggedUser()->customer_id }}"><b>Your</b> elysiumnetwork    <b>REFERRAL LINK: https://www.elysiumnetwork.io/{{ loggedUser()->customer_id }}</b></p>
                    <p onclick="copyLink(this,event)" attr_href="https://www.elysiumcapital.io/{{ loggedUser()->customer_id }}" style="margin-top: 20px;"><b>Your</b> elysiumcapital     <b>REFERRAL LINK: https://www.elysiumcapital.io/{{ loggedUser()->customer_id }}</b></p>
                    
                </div>
                @if($holding_tank)
                <div class="col-sm-5" style="margin-top: 15px;">
                    <div class="row moduleConfig holdingTank">
                        <div class="col-sm-12">
                            {!! Form::open(['class' => 'form-horizontal userconfig','id' => 'userconfig']) !!}
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span aria-hidden="true"
                                          class="icon-tag"></span> Holding Tank Configuration
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6 col-30" style="margin-top: 10px;">
                                            <sapn class="holding_style"> Holding Tank</sapn>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 col-60" style="margin-top: 10px;">
                                            <div class="toggleSwitchWrapper">
                                                <sapn class="holding_style">Off</sapn>
                                                <div class="switch" data-target="holding_tank">
                                                    <span class="trigger @if(!$user->holding_tank_active) left @else  right @endif"></span>
                                                </div>
                                                <sapn class="holding_style">On</sapn>
                                                <input type="hidden" class="holding_tank" name="holding_tank_active" value="{{$user->holding_tank_active}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 col-30" style="margin-top: 10px;">
                                            <button type="button" value="Save"
                                                    class="form-control ladda-button btn green button-submit" data-style="contract"
                                                    name="amount">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row" style="background-image: linear-gradient(#41464d, #101820); display: flex; flex-wrap: wrap; align-items: center;">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="text-align: center;">
            <!-- <div class="row"> -->
                <div id="clockdiv" style="padding: 20px 0;">
                  <div>
                    <span class="days"></span>
                    <div class="smalltext">Days</div>
                  </div>
                  <div>
                    <span class="hours"></span>
                    <div class="smalltext">Hours</div>
                  </div>
                  <div>
                    <span class="minutes"></span>
                    <div class="smalltext">Minutes</div>
                  </div>
                  <div>
                    <span class="seconds"></span>
                    <div class="smalltext">Seconds</div>
                  </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7" style=" display: flex; padding-left: 0px !important;">
            <div style="display: flex; margin: auto;">
               <div class="info_group" data-toggle="modal" data-target="#promotion_modal">
                    <img class="info_img" src="../images/Info.png" style="width: 45px; height: 45px;" />
                    <p class="info_text" style="text-transform: uppercase; color: #fff; margin: 10px 0;">info</p>
                </div>
                <div class="sell_group" style="text-align: center; align-self: center; padding: 20px 0;">
                    <h2 class="title_sell_get">Sell 3 & get yours free!</h2>
                    <div style="display: flex;">
                        <div class="radio-list" style="margin: auto;">
                            <label class="radio-container">
                                <h3 class="checkbox-name1" style="margin-right: 10px !important;margin-top: 2px !important; color: #fff; font-family: DIN Pro Condensed Medium;">1</h3>
                                <span class="{{isset($ibCount) && $ibCount >= 1 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                            </label>
                            <label class="radio-container">
                                <h3 class="checkbox-name1" style="margin-right: 10px !important;margin-top: 2px !important; color: #fff; font-family: DIN Pro Condensed Medium;">2</h3>
                                <span class="{{isset($ibCount) && $ibCount >= 2 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                            </label>
                            <label class="radio-container">
                                <h3 class="checkbox-name1" style="margin-right: 10px !important;margin-top: 2px !important; color: #fff; font-family: DIN Pro Condensed Medium;">3</h3>
                                <span class="{{isset($ibCount) && $ibCount >= 3 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                            </label>
                            <label class="radio-container">
                                <h3 style="margin-top: 2px !important; color: #fff; font-family: DIN Pro Condensed Medium;"> = <span> € </span>{{isset($promotion_amount) && $promotion_amount ? $promotion_amount : '0'}}</h3>
                                
                            </label>
                        </div>
                    </div>
                </div> 
                <div class="other_group" style="text-align: center; align-self: center; margin: 0 auto;">
                    <img class="bo123_img" src="../images/123BO.gif" />
                </div>
            </div>
        </div>
        <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
            <div class="row" style="display: inline; vertical-align: middle;">
                <div class="other_group" style="text-align: center; align-self: center; margin: 0 auto;">
                    <img class="info_img" src="../images/123BO.gif" style="width: 250px; height: auto;" />
                </div>
            </div>
        </div> -->
    </div>
    <div class="row">
        <div class="col-sm-10">

             <div class="row careerWrapper auto_placement_settings" style="align-items: center;display: flex; margin-top: 35px; margin-bottom: 5px;" >
                <div style="float: left;margin-left: 15px;display: flex;align-items: center;"><h3 style="margin-top: 2px;margin-bottom: 0px;">AUTOMATIC PLACEMENT SETTINGS</h3></div>
                <div class="radio-list" style="display: flex;align-items: center;margin-left: 15px;">
                    {{--<label class="radio-container">--}}
                        {{--<input type="radio" name="placement" value="auto"--}}
                               {{--data-title="AUTOMATIC"--}}
                               {{--checked="checked"/>--}}
                        {{--<span class="checkbox-circle"></span>--}}
                        {{--<h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">AUTOMATIC</h3>--}}
                    {{--</label>--}}
                    <label class="radio-container">
                        <input type="radio" name="placement" value="lleg"
                               data-title="LEFT LEG" {{($user->repoData && $user->repoData->default_binary_position == 1)?'checked':''}}/>
                        <span class="checkbox-circle"></span>
                        <h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">LEFT LEG</h3>
                    </label>
                    <label class="radio-container">
                        <input type="radio" name="placement" value="auto"
                               data-title="rleg"
                               {{($user->repoData && $user->repoData->default_binary_position == 2)?'checked':''}}/>
                        <span class="checkbox-circle"></span>
                        <h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">RIGHT LEG</h3>
                    </label>
                    {{--<label class="radio-container">--}}
                        {{--<input type="radio" name="placement" value="sleg"
                               data-title="STRONG LEG" disabled="" />--}}
                        {{--<span class="checkbox-circle"></span>--}}
                        {{--<h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">STRONG LEG</h3>--}}
                    {{--</label>--}}
                    {{--<label class="radio-container">--}}
                        {{--<input type="radio" name="placement" value="wleg"
                               data-title="WEAK LEG" disabled="" />--}}
                        {{--<span class="checkbox-circle"></span>--}}
                        {{--<h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">WEAK LEG</h3>--}}
                    {{--</label>--}}
                    
                    {{--<label class="radio-container">--}}
                        {{--<input type="radio" name="placement" value="htank" data-title="LEFT LEG" disabled="" />--}}
                        {{--<span class="checkbox-circle"></span>--}}
                        {{--<h3 class="checkbox-name" style="margin-left: 34px !important;margin-top: 2px !important;">HOLDING TANK</h3>--}}
                    {{--</label>--}}
                    
                </div>

            </div>
            
            <div class="row careerWrapper">
                <div class="col-sm-12">
                    <h3>Career Status</h3>
                </div>
                <div class="col-sm-12">
                    <div style="display: flex;">
                        @foreach($ranks as $eachRank)
                            @if($currentRank && $eachRank->id <= $currentRank->rank->id)
                                <div class="careerStatus red">
                                    @if($eachRank->id > 10 )
                                        <img src="../images/earnings/career-iconD-red.png"/>
                                    @else
                                        <img src="../images/earnings/career-icon-red.png"/>
                                    @endif
                                    <h4>{{ $eachRank->name }}</h4>
                                </div>
                            @else
                                <div class="careerStatus gray">
                                    @if($eachRank->id > 10 )
                                        <img src="../images/earnings/career-iconD-gray.png"/>
                                    @else
                                        <img src="../images/earnings/career-icon-gray.png"/>
                                    @endif
                                    <h4>{{ $eachRank->name }}</h4>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
    </div>
    <div class="row qualifiedWrapperBox">
        <div class="col-sm-12">
            <h3>EARNINGS <span class="activeEarning"><i class="square"></i> <i class="square green"></i> Active</span>
                <span class="inactiveEarning"><i class="square"></i> Inactive</span>
                <a href="{{ scopeRoute('packageUpgrade') }}" style="color: #343246;"> <span class="upgradeEarning">
                        <i class="fa fa-plus-circle" style="margin-right: 2px"></i>UPGRADE</span> </a>
                <a href="javascript:;" style="color: #343246;" class="re-instate"> <span class="upgradeEarning">
                    <span style="background-color: #07b790; border-radius: 12px !important; width: 24px; text-align: center; margin-right: 2px">
                        <i class="fa fa-undo" style="color: #dcddde; font-size: 16px !important; font-weight: bold; padding-left: 3px;"></i>
                    </span>
                        Re-Instate</span> </a>
            </h3>
        </div>
        @php
            $slNO = 1;
            $month = 0;
            $year = 0;
        @endphp

        <div class="col-sm-12">
            <div class="row">
            @foreach($commissions->chunk(4) as $chunk)
                <div class="col-sm-6">
                    @foreach($chunk as $commission)
                    @php
                        $month += $commission['monthly'];
                        $year += $commission['yearly'];
                    @endphp
                        <div class="row">
                            <div class="col-sm-12 earningsGrid">
                                <div class="row">
                                    <div class="panel-group d-accordion">
                                        <div class="panel panel-default">
                                            <div class="col-sm-3 @if(($commission['commission']->registry['code'] === 'PFC')
                                                                 || ($commission['commission']->registry['code'] === 'QBP')) gridGreen
                                                                 @else gridRed @endif">
                                                <h3>0{{ $slNO }}</h3><span class="line"></span>
                                                <h3><?php if(($commission['commission']->registry['code'] === 'DCU')): ?>DCM<?php else :?><?php echo e($commission['commission']->registry['code']); ?><?php endif;?></h3>
                                            </div>
                                            <div class="col-sm-9 @if(($commission['commission']->registry['code'] === 'PFC')
                                                                 || ($commission['commission']->registry['code'] === 'QBP')) gridWhite gridGreen-text
                                                                 @elseif ($commission['eligibility']) gridWhite @else gridWhite gridGray-text @endif">
                                                <h3> <?php if(($commission['commission']->registry['code'] === 'DCU')): ?>Dynamic Compression Multi-Tier Bonus<?php else :?><?php echo e($commission['commission']->registry['name']); ?><?php endif;?> <span
                                                            class="info" data-toggle="modal"
                                                            data-target="#{{ $commission['commission']->registry['code'] }}modal"><img
                                                                src="../images/earnings/info-icon.png"> </span></h3>
                                                <div class="row gridWhite-inner">
                                                    <div class="col-sm-5">
                                                        <h3>THIS MONTH</h3>
                                                        {{--                                                        <h5><span>€</span>{{ $commission['monthly'] }}</h5>--}}
                                                        <h5><span>{{ currency($commission['monthly']) }}</span></h5>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <h3>THIS YEAR</h3>
                                                        {{--                                                        <h5><span>€</span>{{ $commission['yearly'] }}</h5>--}}
                                                        <h5><span>{{ currency($commission['yearly']) }}</span></h5>
                                                    </div>
                                                    <div class="col-sm-2 panel-heading collapsed showCommissionTable"
                                                         data-code="{{ $commission['commission']->registry['code'] }}"
                                                         data-id="{{ $commission['commission']->moduleId }}"
                                                         data-toggle="collapse"
                                                         data-parent=".d-accordion"
                                                         href="#{{ $commission['commission']->registry['code'] }}">
                                                        <i class="fa fa-angle-down pull-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div id="{{ $commission['commission']->registry['code'] }}"
                                                     class="panel-collapse collapse" aria-expanded="false">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="{{ $commission['commission']->registry['code'] }}modal"
                                     role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content" style="{{$commission['commission']->registry['code'] === 'PMB'? 'width: 66%; max-width: 900px; margin-right: auto; margin-left: auto;':''}} ">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                
                                                 @if($commission['commission']->registry['code'] === 'FPC')
                                                 <h3>PFC commission is generated on credited TVC and GMB commission, and not on pending commissions</h3>
                                                 @else
                                                 <img src="../images/earnings/{{ $commission['commission']->registry['code'] }}.jpg"
                                                 class="img-responsive" style="margin: auto;"/>
                                                 @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal fade" id="promotion_modal" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content" style="width: 66%; max-width: 900px; margin-right: auto; margin-left: auto;">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <img src="../images/earnings/promotion_info.png" class="img-responsive" style="margin: auto;" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $slNO = $slNO + 1;
                        @endphp
                    @endforeach
                </div>
            @endforeach
            </div>
            <div class="total">
                <span class="desc">Total Earnings This month</span>
                {{--<span class="sign">€</span>--}}
                <span class="subtotal">€ {{number_format($month,2)}}</span>
            </div>
            <div class="total">
                <span class="desc">Total Earnings This year</span>
                {{--<span class="sign">€</span>--}}
                <span class="subtotal">€ {{number_format($year,2)}}</span>
            </div>
            <div class="total">
                <span class="desc">Total Earnings {{$oldYear}} </span>
                {{--<span class="sign">€</span>--}}
                <span class="subtotal">€ {{number_format($oldYearCommissionTotal,2)}}</span>
            </div>
        </div>

        {{--@forelse($commissions->chunk(2) as $chunk)--}}
        {{--<div class="commissionContainer">--}}
        {{--@foreach($commissions as $commission)--}}
        {{--<div class="col-sm-6">--}}
        {{--<div class="row">--}}
        {{--<div class="col-sm-12 earningsGrid">--}}
        {{--<div class="row">--}}
        {{--<div class="panel-group d-accordion">--}}
        {{--<div class="panel panel-default">--}}
        {{--<div class="col-sm-3 @if($commission['eligibility']) gridRed @else gridGray @endif">--}}
        {{--<h3>{{ $slNO }}</h3><span class="line"></span>--}}
        {{--<h3>{{ $commission['commission']->registry['code'] }}</h3>--}}
        {{--</div>--}}
        {{--<div class="col-sm-9 gridWhite">--}}
        {{--<h3> {{ $commission['commission']->registry['name'] }} <span--}}
        {{--class="info" data-toggle="modal"--}}
        {{--data-target="#{{ $commission['commission']->registry['code'] }}modal"><i--}}
        {{--class="fa fa-info-circle"></i> </span></h3>--}}
        {{--<div class="row gridWhite-inner">--}}
        {{--<div class="col-sm-5">--}}
        {{--<h3>THIS MONTH</h3>--}}
        {{--<h5><span>€</span>{{ $commission['monthly'] }}</h5>--}}
        {{--</div>--}}
        {{--<div class="col-sm-5">--}}
        {{--<h3>THIS YEAR</h3>--}}
        {{--<h5><span>€</span>{{ $commission['yearly'] }}</h5>--}}
        {{--</div>--}}
        {{--<div class="col-sm-2 panel-heading collapsed showCommissionTable"--}}
        {{--data-code="{{ $commission['commission']->registry['code'] }}"--}}
        {{--data-id="{{ $commission['commission']->moduleId }}"--}}
        {{--data-toggle="collapse"--}}
        {{--data-parent=".d-accordion"--}}
        {{--href="#{{ $commission['commission']->registry['code'] }}">--}}
        {{--<i class="fa fa-angle-down pull-right"></i>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-12">--}}
        {{--<div id="{{ $commission['commission']->registry['code'] }}"--}}
        {{--class="panel-collapse collapse in" aria-expanded="true">--}}

        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<!-- Modal -->--}}
        {{--<div class="modal fade" id="{{ $commission['commission']->registry['code'] }}modal"--}}
        {{--role="dialog">--}}
        {{--<div class="modal-dialog">--}}
        {{--<!-- Modal content-->--}}
        {{--<div class="modal-content">--}}
        {{--<div class="modal-header">--}}
        {{--<button type="button" class="close" data-dismiss="modal">&times;--}}
        {{--</button>--}}
        {{--<img src="../images/earnings/{{ $commission['commission']->registry['code'] }}.jpg"--}}
        {{--class="img-responsive"/>--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@php--}}
        {{--$slNO = $slNO+1;--}}
        {{--@endphp--}}

        {{--@endforeach--}}
        {{--</div>--}}
        {{--@empty--}}
        {{--@endforelse--}}
        <script>
            function copyLink(element, event) {
                event.preventDefault();
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).attr('attr_href')).select();
                document.execCommand("copy");
                $temp.remove();
                alert('Copied to Clipboard');
            }
        </script>
        <script>
            $(function () {
                $('.showCommissionTable').click(function () {
                    var commissionArea = $(this).attr('data-code');
                    var commissionId = $(this).attr('data-id');
                    loadCommissionTable(commissionId, commissionArea);
                });

                $('input[name="placement"]').change(function(){
                    let value = 1;

                    $('input[name="placement"]').each(function(){
                        if(this.checked)
                        {
                            let item = $(this).val();
                            if(item == 'lleg')
                            {
                                value = 1;
                            }
                            else
                            {
                                value = 2;
                            }
                        }
                    })

                    $.post('{{route("user.dashboard.autoplace")}}',{position:value},function(res){
                        if(res.success)
                        {
                            toastr.success("Configuration Saved Successfully");
                        }
                    })
                })

                
            });

            function loadCommissionTable(commissionId, commissionArea, route) {
                simulateLoading("#" + commissionArea);

                route = route ? route : '{{ scopeRoute('transaction') }}';

                $.post(route, {commissionId: commissionId}, function (response) {
                    $("#" + commissionArea).html(response);
                });
            }
        </script>
        </div>
    </div>
</div>
<style>
    .page-content {
        background: #dcddde;
    }

    .page-bar {
        background: none !important;
    }

</style>


<script>
    $(function () {
        if ({{$holding_tank}}) {
            if ({{$user->holding_tank_active}})
                $('.auto_placement_settings').hide();
            else
                $('.auto_placement_settings').show();
        } else {
            $('.auto_placement_settings').show();
        }
        Ladda.bind('.ladda-button');
        $('.toggleSwitchWrapper .switch').click(function () {
            let trigger = $(this).find('.trigger');
            switch ($(this).data('target')) {
                case 'holding_tank':
                    if (trigger.hasClass('right')) {
                        trigger.removeClass('right');
                        $('.holding_tank').val(0);
                    } else {
                        trigger.addClass('right');
                        $('.holding_tank').val(1);
                    }
                    break;
            }
        });
        var form = $('#userconfig');
        var error1 = $('.alert-danger', form);
        var success1 = $('.alert-success', form);

        //Registration request

        $('.holdingTank .button-submit').click(function () {

            var successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("Configuration Saved Successfully");
                if ($('.holding_tank').val() == 0)
                    $('.auto_placement_settings').show();
                else
                    $('.auto_placement_settings').hide();
            };

            var failCallBack = function (response) {
                Ladda.stopAll();
                var errors = response.responseJSON;
            };

            var options = {
                form: form,
                actionUrl: "{{ route('user.holdingTankActive.save') }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });
    });

</script>

<style>
    .toggleSwitchWrapper {
        display: flex;
        color: #757272;
    }

    .toggleSwitchWrapper .switch {
        width: 55px;
        margin: 0 15px;
        position: relative;
        cursor: pointer;
        background: #eee;
        box-shadow: 0px 2px 5px #ddd inset;
        border-radius: 15px !important;
    }

    .toggleSwitchWrapper label {
        margin: 0;
    }

    .toggleSwitchWrapper .switch .trigger {
        width: 20px;
        height: 20px;
        position: absolute;
        left: 0;
        transition: all 0.3s ease;
        background: #11ca8e;
        box-shadow: 1px 0px 3px #4e4e4e;
        display: block;
        border-radius: 50% !important;
    }

    .toggleSwitchWrapper .switch .trigger.right {
        left: 60%;
    }
    .moduleConfig .panel-primary > .panel-heading {
        color: #4d4d4d;
        background-color: #eeeeee;
        border-color: #eeeeee;
        font-weight: 600;
    }

    .moduleConfig .panel-primary > .panel-heading {
        color: #919191;
    }

    .moduleConfig .panel-primary {
        border-color: #eeeeee;
    }

    .moduleConfig .mt-radio-inline {
        padding: 0px;
    }

    .moduleConfig input.form-control {
        margin-bottom: 10px;
    }

    .ajaxValidationError.has-error .help-block-error {
        opacity: 1 !important;
    }

    .mt-radio-inline label.mt-radio {
        padding: 0px;
    }
    .holding_style {
        font-weight: 600;
        font-size: 15px;
    }
    @media (min-width: 1300px) { 
        .col-30 {
            width: 30%;
        }
        .col-60 {
            width: 40%;
        }
    }
</style>

<style type="text/css">
    #clockdiv{
        font-family: sans-serif;
        color: #fff;
        display: inline-block;
        font-weight: 100;
        text-align: center;
        font-size: 30px;
        margin-top: 0.75em;
    }

    #clockdiv > div{
        padding: 10px 0;
        border-radius: 3px;
        /*background: #00BF96;*/
        display: inline-block;
    }

    #clockdiv div > span{
        padding: 15px;
        border-radius: 3px;
        /*background: #00816A;*/
        background: url(../images/CountdownSquare.png) no-repeat;
        background-size: 60px 60px;
        /*display: inline-block;*/
    }

    .smalltext{
        padding-top: 15px;
        font-size: 16px;
        font-family: 'DIN Pro Condensed Medium', sans-serif;
        text-transform: uppercase;
    }

    .checkbox-circle1 {
        width: 24px;
        height: 24px;
        border-radius: 50% !important;
        border: 2px solid #fff;
        background: #41464d;
        display: inline-block;
        padding: 0;
        vertical-align: middle;
        transition: border .2s ease-in-out;
        margin-bottom: 6px;
    }

    .checkbox-circle2 {
        width: 24px;
        height: 24px;
        border-radius: 50% !important;
        border: 2px solid #fff;
        background: #d2262f;
        display: inline-block;
        padding: 0;
        vertical-align: middle;
        transition: border .2s ease-in-out;
        margin-bottom: 6px;
    }

    .check-name1 {
        display: inline-block;
        font-size: 14px;
    }

    .bo123_img {
        width: 250px;
        height: auto;
    }
    .info_group {
        text-align: center; 
        align-self: center; 
        margin-right: 1.5vw; 
        padding-top: 1.75em;
        cursor: pointer;
    }
    .info_group_link {

        text-decoration: none !important;
    }
    /*.promotion-modal-dialog {
        width: 70%;
    }*/
    .title_sell_get {
        text-transform: uppercase; 
        color: #fff; 
        font-weight: bold; 
        margin-top: 0; 
        font-family: DIN Pro Condensed Medium; 
        font-size: 35px;
    }
    .other_group {
        transform: translate(4vw, 0);
    }
    @media (min-width: 1500px) {
        .sell_group {
            margin-right: 2.5vw;
            margin-left: 2.5vw;
        }
        .other_group {
        transform: translate(6vw, 0);
    }
    }
    @media (max-width: 1500px) {
        .sell_group {
            margin-right: 1.0vw;
            margin-left: 1.0vw;
        }
    }

    @media (max-width: 1440px) {
        .sell_group {
            margin-right: 12px;
            margin-left: 2px;
        }
    }
    @media (max-width: 1300px) {
        .sell_group {
            margin-right: 10px;
            margin-left: 1px;
        }
        .info_group {
            margin-right: 0.5vw;
        }
        .title_sell_get {
            font-size: 28px;
        }
    }
    @media (max-width: 1200px) {
        .sell_group {
            margin-right: 5px;
            margin-left: 5px;
        }
    }
    @media (max-width: 1160px) {
        .bo123_img {
            width: 230px;
        }
        .title_sell_get {
            font-size: 25px;
        }
    }
    @media (max-width: 1100px) {
        .bo123_img {
            width: 190px;
        }
    }
    @media (max-width: 1000px) {
        .bo123_img {
            width: 250px;
        }
        .sell_group {
            margin-right: 25px;
            margin-left: 25px;
        }
        .title_sell_get {
            font-size: 35px;
        }
    }
</style>
<script type="text/javascript">
    var server_time = moment("<?=date('Y-m-d H:i:s',$shareTime['serverTimeStamp'])?>");
    function getTimeRemaining(endtime) {
      // var t = Date.parse(endtime) - Date.parse(new Date());
      server_time += 1000;
      var t = Date.parse(endtime) - server_time;
      t = t <= 0 ? 0 : t;
      var seconds = Math.floor((t / 1000) % 60);
      var minutes = Math.floor((t / 1000 / 60) % 60);
      var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
      var days = Math.floor(t / (1000 * 60 * 60 * 24));
      return {
        'total': t,
        'days': days,
        'hours': hours,
        'minutes': minutes,
        'seconds': seconds
      };
    }

    function initializeClock(id, endtime) {
      var clock = document.getElementById(id);
      var daysSpan = clock.querySelector('.days');
      var hoursSpan = clock.querySelector('.hours');
      var minutesSpan = clock.querySelector('.minutes');
      var secondsSpan = clock.querySelector('.seconds');

      function updateClock() {
        var t = getTimeRemaining(endtime);

        daysSpan.innerHTML = ('0' + t.days).slice(-2);
        hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
        minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
        secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

        if (t.total <= 0) {
          clearInterval(timeinterval);
        }
      }

      updateClock();
      var timeinterval = setInterval(updateClock, 1000);
    }

    var deadline;
    // var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
    if ({{$promotionalBonus}}) {

        var datetime = '{{isset($offerEndAt) ? $offerEndAt : ""}}';
        if(datetime)
        {
            datetime = datetime.split(' ').join('T');    
        }
        
        deadline = new Date(datetime);
        initializeClock('clockdiv', deadline);
    } else {
        deadline = new Date(Date.parse(new Date()) + 3000);
        initializeClock('clockdiv', deadline);
    }
</script>
@endsection
<style>
    .row.linksHolder {
        margin-bottom: 20px;
    }
</style>
