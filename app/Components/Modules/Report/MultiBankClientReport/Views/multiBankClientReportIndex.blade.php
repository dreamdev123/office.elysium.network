@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
    @if(getScope()=='user')
        @include('_includes.reports_nav')
        <div class="heading" style="margin-top: 50px">
            <h3>{{ _mt($moduleId, 'MultiBankClientReport.Client_Report') }}</h3>
        </div>
    @endif()

    <div class="row">
        <div class="col-md-12 reportFilters"></div>
        <div class="col-md-12 reportContainer"></div>
    </div>
    <script>
        "use strict";
        $(function () {
            multiBankClientReportFilters();
        });

        function multiBankClientReportFilters() {
            simulateLoading('.reportFilters');
            $.get('{{ scopeRoute('multiBankClientReport.filters') }}', function (response) {
                $('.reportFilters').html(response);
                @if ('admin' == getScope())
                $('.filterRequest').trigger('click');
                @else
                fetchMultiBankClientReport();
                @endif
            });
        }

        function fetchMultiBankClientReport(route) {
            simulateLoading('.reportContainer');
            route = route ? route : '{{ scopeRoute('multiBankClientReport.clients') }}';
            $.post(route, $('.filterForm').serialize(), function (response) {
                $('.reportContainer').html(response);
                Ladda.stopAll();
            });
        }
    </script>
    <style>
        @media (max-width: 767px) {
            .reportFilters .eachFilter.operation.col-md-3.col-sm-3 {
                width: 50%;
                float: left;
            }
        }

        @media (max-width: 480px) {
            .reportFilters .eachFilter.operation.col-md-3.col-sm-3 {
                width: 100%;
                float: none;
            }

            .reportFilters .filters .eachFilter button {
                margin: 10px 0px;
            }
        }
    </style>
@endsection
