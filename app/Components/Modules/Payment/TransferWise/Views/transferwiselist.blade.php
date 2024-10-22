@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
   <div class="heading" style="margin-top: 50px">
        <h3> {{ _mt('Payment-Transferwise','SafeTransaction.SafeTran_List') }}</h3>
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
            route = route ? route : '{{ route("admin.Transferwise.list") }}';
           
            $.get(route, {}, function (response) {
                $('.referralListContainer').html(response);
            });
        }
    </script>
@endsection
