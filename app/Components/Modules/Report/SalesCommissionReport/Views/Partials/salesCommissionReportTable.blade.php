@if($salesData->count())
<style type="text/css">

    .mainBreadcrumb{
        margin-top: 30px;
    }
    .page-content-wrapper .page-content{
        padding: 0px 20px 0px 20px;
    }
    button{
        border-radius: 3px !important;
    }
    #example_filter label{
        display: flex;
        align-items: center;
    }

    #example_filter label input{
        margin-left: 10px;
    }
    table.dataTable.no-footer{
        border-bottom:unset !important;
    }
    #example_wrapper{
        width: 99% !important;
    }
    .table-panels {
        border-top: 2px solid #504f4fdd;
        background: #fff;
        overflow: hidden;
        padding: 15px;
        margin: auto;
        box-shadow: 0px 2px 4px #ddd;
        margin-top: 20px;
        margin-bottom: 10px;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    {{--<div class="reportOptions">
        <button type="button" class="btn btn-outline blue downloadPdf"><i
                    class="fa fa-file-pdf-o"></i>{{ _mt($moduleId,'SalesCommissionReport.pdf') }}</button>
        <button type="button" class="btn btn-outline green downloadExcel"><i
                    class="fa fa-file-excel-o"></i>{{ _mt($moduleId,'SalesCommissionReport.excel') }}</button>
        <button type="button" class="btn btn-outline red downloadCsv"><i
                    class="fa fa-file-text-o"></i>{{ _mt($moduleId,'SalesCommissionReport.csv') }}</button>
        <button type="button" class="btn btn-outline dark printReport"><i
                    class="fa fa-print"></i>{{ _mt($moduleId,'SalesCommissionReport.print') }}</button>
    </div>--}}

    <div class="table-responsive table-panels">
        <table id="example" class="table table-striped table-bordered table-hover dataTable dtr-inline reporttable" role="grid" aria-describedby="sample_1_info">
            <thead>
            <tr>
                <th style="text-align: center;"> No </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.user') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.package') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.total') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.total_commission') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.balance') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.paid') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.pending') }} </th>
                <th> {{ _mt($moduleId,'SalesCommissionReport.date') }} </th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesData as $key => $sale)
                <tr>
                    <td align="center"></td>
                    <td> {{ usernameFromId($sale->user_id) }} </td>
                    <td>  
                        @foreach($sale->products as $product)
                            {{  $product->package ? $product->package->name : '' }}
                        @endforeach 
                    </td>
                    <td> {{ currency($sale->subtotal) }} </td>
                    @php
                    $approved = $commission_status[$key]['approved'] != null ? $commission_status[$key]['approved']->amount : 0;
                    $pending = $commission_status[$key]['pending'] != null ? $commission_status[$key]['pending']->amount : 0;
                    $balance = $commission_status[$key]['balance'] != null ? $commission_status[$key]['balance'] : 0;
                    $total = $approved + $pending + $balance;
                    @endphp
                    <td> {{ currency($total) }} </td>
                    <td> {{ currency($balance) }} </td>
                    <td> {{ currency($approved) }} </td>
                    <td> {{ currency($pending) }} </td>
                    <td> {{ $sale->created_at }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    
@else
    {{ _mt($moduleId,'SalesCommissionReport.no_sale_available') }}
@endif
<script type="text/javascript" src="{{asset('js/datatable.js')}}"></script>
<script>
  $(function(){
    $("#example").dataTable();
  })
  var t = $('#example').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
</script>
<script type="text/javascript">
    'use strict';

    $('.paginationWrapper .pagination li a').click(function (e) {
        e.preventDefault();
        var route = $(this).attr('href');
        fetchSalesReport(route);
    });

    var TableDatatablesButtons = function () {

        var initTable1 = function () {
            var table = $('.reporttable');
            var oTable = table.dataTable({

                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "_MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found"
                },
                // Or you can use remote translation file
                //"language": {
                //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
                //},


                buttons: [
                    // {extend: 'print', className: 'btn dark btn-outline'},
                    // {extend: 'pdf', className: 'btn green btn-outline'},
                    // {extend: 'excel', className: 'btn yellow btn-outline '},
                    // {extend: 'csv', className: 'btn purple btn-outline '},

                ],
                // setup responsive extension: http://datatables.net/extensions/responsive/
                responsive: true,
                //"ordering": false, disable column ordering
                "paging": false, //disable pagination
                "searching": false,
                "bInfo": false,

                "order": [
                    [0, 'asc']
                ],
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 10,
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                initTable1();
                //initAjaxDatatables();
            }

        };
    }();
    jQuery(document).ready(function () {
        TableDatatablesButtons.init();
    });

    // Download report as pdf
    $('.downloadPdf').click(function () {
        $.post('{{ route('salesCommissionReport.download.pdf') }}', $('.filterForm').serialize(), function (response) {
            window.open(response.link);
        });
    });

    // Download report as Excel
    $('.downloadExcel').click(function () {
        $.post('{{ route('salesCommissionReport.download.excel') }}', $('.filterForm').serialize(), function (response) {
            window.open(response.link);
        });
    });

    // Download report as csv
    $('.downloadCsv').click(function () {
        $.post('{{ route('salesCommissionReport.download.csv') }}', $('.filterForm').serialize(), function (response) {
            window.open(response.link);
        });
    });

    // print report
    $('.printReport').click(function () {
        $.post('{{ route('salesCommissionReport.print') }}', $('.filterForm').serialize(), function (response) {
            var HTML = response;

            var WindowObject = window.open("", "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
            WindowObject.document.writeln(HTML);
            WindowObject.document.close();
            WindowObject.focus();
            WindowObject.print();
            WindowObject.close();
        });
    });

    $('.reporttable table-scrollable').slimScroll({height: '400px'});
</script>
