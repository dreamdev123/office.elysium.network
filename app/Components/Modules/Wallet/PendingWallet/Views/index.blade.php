@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
    @if(getScope() == "user")
        @include('_includes.wallet_nav')
    @endif

    <div class="walletWrapper">
        <div class="row topRow">
            <div class="balance col-md-4">
                <div class="moneyBox">
                    <div class="walletIco col-md-3">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span class="balanceAmount col-md-9">
                        <h4>{{_mt('Wallet-Ewallet','Ewallet.current_balance') }}</h4>
                        <p id="balanceAmount" data-amount="{{ $balance }}">{{ currency($balance) }}</p>
                    </span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="walletAjaxPartials row" data-unit="overView"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        "use strict";
        //Document ready functions
        $(function () {
            //Refresh ajax details
            $('.walletAjaxPartials').each(function () {
                refreshAjaxData($(this));
            });
        });

        //Ajax info retrieval
        function refreshAjaxData(elem, route, args, postParams) {
            simulateLoading(elem);
            route = route ? route : '{{ scopeRoute("pendingWallet.unit") }}';
            var options = {unit: elem.attr('data-unit'), args: args};

            if (postParams) $.extend(options, postParams);

            return $.post(route, options, function (response) {
                elem.html(response);
            });
        }

        function resizePartial(action) {
            switch (action) {
                case 'contract':
                    $('body').addClass('page-sidebar-closed');
                    $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
                    $('.walletWrapper .topRow > div').first()
                        .removeClass('col-md-4').addClass('col-md-3');
                    $('.walletWrapper .topRow > div').last()
                        .removeClass('col-md-8').addClass('col-md-9');
                    break;
                default:
                    $('body').removeClass('page-sidebar-closed');
                    $('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
                    $('.walletWrapper .topRow > div').first()
                        .addClass('col-md-4').removeClass('col-md-3');
                    $('.walletWrapper .topRow > div').last()
                        .addClass('col-md-8').removeClass('col-md-9');
                    break;
            }
        }

        //Navigation
        $('body').off('click', '.eachNav').on('click', '.eachNav', function () {
            var me = $(this);
            resizePartial();
            $(this).addClass('loading').siblings().removeClass('loading');
            $('.walletAjaxPartials').attr('data-unit', $(this).attr('data-unit'));
            refreshAjaxData($('.walletAjaxPartials')).done(function () {
                me.addClass('active').removeClass('loading').siblings().removeClass('active');
            });
        });
    </script>
@endsection
