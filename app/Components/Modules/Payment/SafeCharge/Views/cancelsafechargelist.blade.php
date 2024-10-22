@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
   <div class="heading" style="margin-top: 50px">
        <h3> {{ _mt('Payment-SafeCharge','SafeTransaction.subscriptionlist') }}</h3>
    </div>
    <div class="referralListWrapper">
        <div class="summaryData row" style="margin-bottom: 15px;">
            <div class="col-md-6">
                <div class="referralSummary">
                    <!-- STAT -->
                  
                </div>
            </div>
            <div class="col-md-6">
                <div id="chartContainer" class="chartContainer"></div>
            </div>
        </div>
        <div class="referralListContainer">

        </div>
    </div>
    <script>
        'use strict';

        loadRefferalList();

        $('body').on('click', '.paginationWrapper .pagination li a', function (e) {
            e.preventDefault();
            var route = $(this).attr('href');
            loadRefferalList(route);
        });


        function loadRefferalList(route) {
            simulateLoading('.referralListContainer');
            route = route ? route : '{{ route("admin.subscription.list") }}';
           
            $.get(route, {}, function (response) {
                $('.referralListContainer').html(response);
            });
        }

        $('.subscription').click(function(){
            var address = $(this).attr('subscription');
            var self = this;
            $.ajax({
                url:'{{route("SafeCharge.cancel_subscription")}}',
                method:'POST',
                data:{address:address},
                success:function(res){
                    loadRefferalList();
                }
            })
        })
    </script>
@endsection
