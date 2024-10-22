<style>
    .table-scroll {
        position: relative;
        width:100%;
        margin: auto;
        display:table;
    }
    .table-wrap {
        width: 100%;
        display:block;
        overflow: auto;
        position:relative;
        z-index:1;
    }
    
    .table-advance thead tr th
    {
        font-size: 12px !important;
    }
    .table-scroll table {
        width: 100%;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-scroll th, .table-scroll td {
        padding: 5px 10px;
        background: #fff;
        vertical-align: top;
    }
    .faux-table table {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        pointer-events: none;
    }
    .faux-table table tbody {
        visibility: hidden;
    }
    /* shrink cells in cloned table so that the table height is exactly 300px so that the header and footer appear fixed */
    .faux-table table tbody th, .faux-table table tbody td {
        padding-top:0;
        padding-bottom:0;
        border-top:none;
        border-bottom:none;
        line-height:0.1;
    }
    .faux-table table tbody tr + tr th, .faux-table tbody tr + tr td {
        line-height:0;
    }
    .faux-table thead th, .faux-table tfoot th, .faux-table tfoot td,
    .table-wrap thead th, .table-wrap tfoot th, .table-wrap tfoot td{
        background: #ccc;
    }
    .faux-table {
        position:absolute;
        top:0;
        right:0;
        left:0;
        bottom:0;
        overflow-y:scroll;
    }
    .faux-table thead, .faux-table tfoot, .faux-table thead th, .faux-table tfoot th, .faux-table tfoot td {
        position:relative;
        z-index:2;
    }

    #referral-table {

    }
</style>

<div class="clientListWrapper" data-user="{{ $user->id }}">
    <div class="clientListContainer">
        @if($clients->count())
            <div>
                <table class="table table-bordered table-hover reporttable">
                    <thead>
                    <tr>
                        <th> {{ _mt('General-ClientList', 'ClientList.sl_no') }}</th>
                        <th> {{ _mt('General-ClientList', 'ClientList.fullname') }}</th>
                        <th> {{ _mt('General-ClientList', 'ClientList.email') }}</th>
                        <th> {{ _mt('General-ClientList', 'ClientList.created') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $loop->iteration  }}</td>
                            <td>{{ $client->name }}  </td>
                            <td>{{ $client->email }} </td>
                            <td>{{ date('Y-m-d h:i:s', strtotime($client->reg_date)) }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{ _mt('General-ClientList','ClientList.noClients') }}
        @endif
    </div>
</div>

<script>
    'use strict';

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
                "paging": true, //disable pagination
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
                "pageLength": 20,
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
</script>
