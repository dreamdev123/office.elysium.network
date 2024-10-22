@extends('User.Layout.master')
@section('content')
    <div class="row personalInfoGridWrapper">
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
    </div>

    <div class="row membership-section-wrapper">
        <div class="col-sm-12 license-membership-content">
            <p>YOUR ELYSIUM MEMBERSHIP WILL END IN</p>
            <div class="expirydate-context">{{$numExpiryDates}}</div>
            <p style="margin-right: 50px;">DAYS</p>
            <a href="{{route('user.expirepayment')}}"><button class="subscription-update-btn">UPDATE</button></a>
        </div>
    </div>

    <div class="row row-midnight-bg">
        <div class="col-sm-12 description-content">
            <p>YOUR ELYSIUM NETWORK REFERRAL LINK</p>
            <div class="clipboard-btn" style="margin-right: 50px;" onclick="copyLink(this,event)" attr_href="https://www.elysiumnetwork.io/{{ loggedUser()->customer_id }}">Copy</div>
            <p>YOUR ELYSIUM INSIDER REFERRAL LINK</p>
            <div class="clipboard-btn" onclick="copyLink(this,event)" attr_href="https://www.elysiuminsider.io/{{ loggedUser()->customer_id }}">Copy</div>
        </div>
    </div>

    <div class="row row-night-bg">
        <div class="col-sm-12 description-content">
            <p style="margin-right: 50px;">PLACEMENT SETTINGS</p>
            <div class="radio-list" style="display: flex; align-items: center; margin-left: 15px;">
                <label class="radio-container">
                    <input type="radio" class="autoplacement-submit" name="auto-placement" value="auto" data-title="AUTOMATIC" {{($userConfig && $userConfig->status == 1)?'checked':''}} />
                    <span class="checkbox-circle"></span>
                    <h3 class="checkbox-name">AUTOMATIC</h3>
                </label>
                <label class="radio-container auto_placement_settings">
                    <input type="radio" name="placement" value="lleg" data-title="LEFT LEG" {{($user->repoData && $user->repoData->default_binary_position == 1)?'checked':''}}/>
                    <span class="checkbox-circle"></span>
                    <h3 class="checkbox-name">LEFT LEG</h3>
                </label>
                <label class="radio-container auto_placement_settings">
                    <input type="radio" name="placement" value="rleg" data-title="RIGHT LEG" {{($user->repoData && $user->repoData->default_binary_position == 2)?'checked':''}} />
                    <span class="checkbox-circle"></span>
                    <h3 class="checkbox-name">RIGHT LEG</h3>
                </label>
                <label class="radio-container">
                    <input type="radio" class="holdingTank-submit" name="holding-tank" value="holding_tank" data-title="HOLDING TANK" {{($user->holding_tank_active) ? 'checked' : ''}} />
                    <span class="checkbox-circle"></span>
                    <h3 class="checkbox-name">HOLDING TANK</h3>
                </label>
            </div>
        </div>
    </div>

    <div class="row row-midnight-bg">
        <div class="col-sm-12 description-content">
            <p style="margin-right: 10px;">RANK STATUS</p>
            @foreach($ranks as $eachRank)
                @if($currentRank && $eachRank->id <= $currentRank->rank->id)
                    <div class="active-rank-circle">
                        @if($eachRank->id > 10 )
                            <img src="{{asset('images/earnings/jewelry.png')}}" style="margin: auto; width: 30px; height: 30px;">
                        @else
                            <span style="margin: auto; font-size: 30px;">{{ $eachRank->id == 1 ? 'B' : $eachRank->id - 1 }}</span>
                        @endif
                    </div>
                @else
                    <div class="inactive-rank-circle">
                        @if($eachRank->id > 10 )
                            <img src="{{asset('images/earnings/jewelry.png')}}" style="margin: auto; width: 30px; height: 30px;">
                        @else
                            <span style="margin: auto; font-size: 30px;">{{ $eachRank->id == 1 ? 'B' : $eachRank->id - 1 }}</span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="row row-night-bg">
        <div class="col-sm-12 description-content">
            <p>ACTIVE PERSONAL IB’S</p>
            <p style="margin-left: 40px; margin-right: 10px;">LEFT TEAM</p>
            <div style="padding: 5px 25px; background-color: #41464d;" >{{ $left_refferal_users }}</div>
            <p style="margin-left: 30px; margin-right: 10px;">RIGHT TEAM</p>
            <div style="padding: 5px 25px; background-color: #41464d;" >{{ $right_refferal_users }}</div>
            <p style="margin-left: 40px; margin-right: 10px;">ACTIVE PERSONAL CLIENTS</p>
            <div style="padding: 5px 25px; background-color: #41464d;" >{{ $clients }}</div>
        </div>
    </div>

    <div class="row row-commission-table">
        <div>
            @php
                $slNO = 1;
                $month = 0;
                $year = 0;
            @endphp
            @foreach($commissions->chunk(4) as $chunk)
                @foreach($chunk as $commission)
                @php
                    $month += $commission['monthly'];
                    $year += $commission['yearly'];
                @endphp
                <div class="row earningsGrid" style="margin: 0;">
                    <div class="col-md-4 commission-cell">
                        <p><?php if(($commission['commission']->registry['code'] === 'DCU')): ?>Dynamic Compression Multi-Tier Bonus<?php else :?><?php echo e($commission['commission']->registry['name']); ?><?php endif;?> (<?php if(($commission['commission']->registry['code'] === 'DCU')): ?>DCM<?php else :?><?php echo e($commission['commission']->registry['code']); ?><?php endif;?>)</p>
                    </div>
                    @if($commission['commission']->registry['code'] === 'FTC')
                        <div class="col-md-6 commission-cell" style="display: flex; align-items: center; justify-content: center;">
                            <div class="radio-list" style="margin: auto;">
                                <label class="radio-container" style="margin-right: 30px;">
                                    <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">1</p>
                                    <span class="{{isset($ibCount) && $ibCount >= 1 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                                </label>
                                <label class="radio-container" style="margin-right: 30px;">
                                    <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">2</p>
                                    <span class="{{isset($ibCount) && $ibCount >= 2 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                                    <p class="checkbox-name1" style="margin-left: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">{{ currency(50) }}</p>
                                </label>
                                <label class="radio-container">
                                    <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">3</p>
                                    <span class="{{isset($ibCount) && $ibCount >= 3 ? 'checkbox-circle2' : 'checkbox-circle1'}}"></span>
                                    <p class="checkbox-name1" style="margin-left: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">{{ currency(150) }}</p>
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3 commission-cell">
                            <p style="margin-right: 10px;">THIS MONTH</p>
                            <div class="amount">{{ currency($commission['monthly']) }}</div>
                        </div>
                        <div class="col-md-3 commission-cell">
                            <p style="margin-right: 10px;">THIS YEAR</p>
                            <div class="amount">{{ currency($commission['yearly']) }}</div>
                        </div>
                    @endif
                    <div class="col-md-2 commission-cell">
                        <span class="info" data-toggle="modal" data-target="#{{ $commission['commission']->registry['code'] }}modal"><img src="{{asset('images/earnings/info.png')}}" style="width: 35px; height: 35px; margin: 20px 5px;"> </span>
                        <span class="collapsed showCommissionTable"
                             data-code="{{ $commission['commission']->registry['code'] }}"
                             data-id="{{ $commission['commission']->moduleId }}"
                             data-toggle="collapse"
                             data-parent=".d-accordion"
                             href="#{{ $commission['commission']->registry['code'] }}">
                            <img src="{{asset('images/earnings/graph.png')}}" style="width: 35px; height: 35px; margin: 20px 5px;">
                        </span>
                    </div>
                    <div class="col-sm-12" style="background-color: #FFFFFF; border: 2px solid #41464d; width: 90%;">
                        <div id="{{ $commission['commission']->registry['code'] }}"
                             class="panel-collapse collapse" aria-expanded="false">

                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="{{ $commission['commission']->registry['code'] }}modal"
                         role="dialog">
                        <div class="modal-dialog" style="width: 70%;">
                            <!-- Modal content-->
                            <div class="modal-content" style="{{$commission['commission']->registry['code'] === 'PMB'? 'width: 66%; max-width: 900px; margin-right: auto; margin-left: auto;':''}} ">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;
                                    </button>
                                    
                                     @if($commission['commission']->registry['code'] === 'FPC')
                                     <h3>PFC commission is generated on credited TVC and GMB commission, and not on pending commissions</h3>
                                     @else
                                     <img src="{{asset('images/earnings')}}/{{ $commission['commission']->registry['code'] }}.png"
                                     class="img-responsive" style="margin: auto;"/>
                                     @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @if($slNO == 5)
                <div class="row">
                    <div class="col-md-10">
                        <hr style="width: 90%;" />
                    </div>
                </div>
                @endif
                @php
                    $slNO = $slNO + 1;
                @endphp
                @endforeach
            @endforeach
        </div>
        <div class="row earningsGrid" style="margin: 0;">
            <div class="col-md-4 commission-cell">
                <p style="margin-right: 10px;">POOL VOLUME</p>
                <div class="amount" style="width: 40%;">{{ currency(0) }}</div>
            </div>
            <div class="col-md-6 commission-cell" style="display: flex; align-items: center;">
                <p style="margin-right: 30px;">SHARES</p>
                <div class="radio-list">
                    <label class="radio-container" style="margin-right: 30px;">
                        <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">1</p>
                        <span class="checkbox-circle1"></span>
                    </label>
                    <label class="radio-container" style="margin-right: 30px;">
                        <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">2</p>
                        <span class="checkbox-circle1"></span>
                    </label>
                    <label class="radio-container" style="margin-right: 30px;">
                        <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">3</p>
                        <span class="checkbox-circle1"></span>
                    </label>
                    <label class="radio-container">
                        <p class="checkbox-name1" style="margin-right: 10px !important; color: #fff; font-family: DIN Pro Condensed Medium;">4</p>
                        <span class="checkbox-circle1"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <hr style="width: 90%;" />
            </div>
        </div>
        
        <div class="row" style="margin: 0;">
            <div class="col-md-4 commission-cell">
                <p>Total</p>
            </div>
            <div class="col-md-3 commission-cell">
                <p style="margin-right: 10px;">THIS MONTH</p>
                <div class="amount">€ {{number_format($month,2)}}</div>
            </div>
            <div class="col-md-3 commission-cell">
                <p style="margin-right: 10px;">THIS YEAR</p>
                <div class="amount">€ {{number_format($year,2)}}</div>
            </div>
        </div>
    </div>
</div>

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

        $('.holdingTank-submit').click(function () {
            let value = 0;
            $('.autoplacement-submit').prop('checked', false);

            $('input[name="holding-tank"]').each(function(){
                if(this.checked)
                {
                    let item = $(this).val();
                    if(item == 'holding_tank')
                    {
                        value = 1;
                    }
                    else
                    {
                        value = 0;
                    }
                }
            })

            $.post('{{route("user.holdingTankActive.save")}}',{holding_tank_active:value},function(res){
                if(res.status)
                {
                    toastr.success("Configuration Saved Successfully");
                    
                    $.post('{{route("user.autoplacementActive.save")}}',{autoplacement_status: 0},function(res){
                        if(res.status)
                        {
                            if (value == 0)
                                $('.auto_placement_settings').show();
                            else
                                $('.auto_placement_settings').hide();
                        }
                    })
                }
            })
        });

        $('.autoplacement-submit').click(function () {
            let value = 0;

            $('.holdingTank-submit').prop('checked', false);

            $('input[name="auto-placement"]').each(function(){
                if(this.checked)
                {
                    let item = $(this).val();
                    if(item == 'auto')
                    {
                        value = 1;
                    }
                    else
                    {
                        value = 0;
                    }
                }
            })

            $.post('{{route("user.autoplacementActive.save")}}',{autoplacement_status:value},function(res){

                if(res.status)
                {
                    toastr.success("Configuration Saved Successfully");
                    $.post('{{route("user.holdingTankActive.save")}}',{holding_tank_active: 0},function(res){
                        if(res.status)
                        {
                            if (value == 0)
                                $('.auto_placement_settings').hide();
                            else
                                $('.auto_placement_settings').show();
                        }
                    })
                    
                }
            })
        });
    });

</script>
<style type="text/css">
    

    .checkbox-circle1 {
        width: 24px;
        height: 24px;
        border-radius: 50% !important;
        border: 2px solid #fff;
        background: #fff;
        display: inline-block;
        padding: 0;
        vertical-align: middle;
        transition: border .2s ease-in-out;
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
    }
</style>
@endsection
