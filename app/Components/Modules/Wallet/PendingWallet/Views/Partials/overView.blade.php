<div class="walletOverview col-md-12">
    @foreach($scripts as $script)
        <script type="text/javascript" src="{{ $script }}"></script>
    @endforeach
    <div class="navWrapper">
        <ul class="navUl">
            <li data-unit="transactionList">
                <i class="icon-wallet"></i>
                <p>{{_mt($moduleId,'Ewallet.transactions') }}</p>
            </li>
        </ul>
    </div>
    <div class="subPartials">
        <div class="transactionList " id="transactionList"></div>
    </div>
</div>

<script type="text/javascript">
    "use strict";

    function loadIncomeChart() {
        simulateLoading('.subPartials');
        $('.subPartials').attr('data-unit', 'transactionList');
        refreshAjaxData($('.subPartials'));
    }

    $(function () {
        loadIncomeChart();
    });
</script>