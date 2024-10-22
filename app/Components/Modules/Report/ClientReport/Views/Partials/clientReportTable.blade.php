
@if(count($clients) > 0)
    <div>
        <table class="table table-bordered table-hover reporttable">
            <thead>
            <tr>
                <th> {{ _mt($moduleId, 'ClientReport.sl_no') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.member_id') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.fullname') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.email') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.created') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $loop->iteration  }}</td>
                    <td>{{ $client['customer_id'] }}  </td>
                    <td>{{ $client['name'] }}  </td>
                    <td>{{ $client['email'] }} </td>
                    <td>{{ date('Y-m-d h:i:s', strtotime($client['created_at'])) }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--        <div class="paginationWrapper">--}}
        {{--            {!! $downlines->links() !!}--}}
        {{--        </div>--}}
    </div>
    <hr/>

     <div class="heading" style="margin-top: 50px; margin-bottom: 20px">
        <h3>{{ _mt($moduleId, 'ClientReport.Tier_Report') }}</h3>
    </div>

    @foreach($tiers as $key=>$tier)
    <?php
        $total_aum = 0;
        foreach ($tier as $value) {
            $total_aum += $value['invested_amount'];
        }
    ?>
    <h4 style="display: flex;">Tier {{$key}}<span style="margin-left: auto">Total AUM: {{$total_aum}}</span></h4>

    <div>
        <table class="table table-bordered table-hover reporttable">
            <thead>
            <tr>
                <th> {{ _mt($moduleId, 'ClientReport.sl_no') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.member_id') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.aum') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.equity') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.commission') }}</th>
                <th> {{ _mt($moduleId, 'ClientReport.created') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tier as $value)
                <tr>
                    <td>{{ $loop->iteration  }}</td>
                    <td>{{ $value['customer_id'] }}  </td>
                    <td>{{ $value['invested_amount'] }}  </td>
                    <td>{{ $value['equity_amount'] }}  </td>
                    <td>{{ $value['commission'] }} </td>
                    <td>{{ date('Y-m-d h:i:s', strtotime($value['created_at'])) }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
@else
    {{ _mt($moduleId, 'ClientReport.No_Clients_found_or_Signed Up') }}
@endif
<script>
    'use strict';
    // $('.paginationWrapper .pagination li a').click(function (e) {
    //     e.preventDefault();
    //     var route = $(this).attr('href');
    //     fetchTreeList(route);
    // });


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
