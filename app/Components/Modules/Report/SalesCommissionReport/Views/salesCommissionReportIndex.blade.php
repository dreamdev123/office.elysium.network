@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12 reportFilters">
        </div>
        <div class="col-md-12 reportContainer">
        </div>
    </div>

    <script>
        "use strict";

        $(function () {
            loadreportFilters();
        });

        function loadreportFilters() {
            $.get('{{ route('salesCommissionReport.filters') }}', function (response) {
                $('.reportFilters').html(response);
                $('.filterRequest').trigger('click')
            });
        }

        function fetchSalesReport(route) {
            simulateLoading('.reportContainer');
            route = route ? route : '{{ route('salesCommissionReport.fetch') }}';
            $.post(route, $('.filterForm').serialize(), function (response) {
                $('.reportContainer').html(response);
                Ladda.stopAll();
            })
        }
    </script>
    <style>
        .page-content {
            overflow: hidden;
        }
    </style>
@endsection
