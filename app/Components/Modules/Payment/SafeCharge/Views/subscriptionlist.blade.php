@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
   <div class="heading" style="margin-top: 30px">
        <h3> {{ _mt('Payment-SafeCharge','SafeTransaction.subscriptionlist') }}</h3>
    </div>
    <div class="referralListWrapper">
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



        function fetchRefferalList(route) {
            simulateLoading('.referralListContainer');
            route = route ? route : '{{ route("admin.SafeCharge.fetch") }}';
            $.post(route, $('.filterForm').serialize(), function (response) {
                $('.referralListContainer').html(response);
                
            })
        }


        function loadRefferalList(route) {
            simulateLoading('.referralListContainer');
            route = route ? route : '{{ route("admin.SafeCharge.subscriptionitems") }}';
           
            $.get(route, {}, function (response) {
                $('.referralListContainer').html(response);
                 
            });
        }

    </script>
@endsection
