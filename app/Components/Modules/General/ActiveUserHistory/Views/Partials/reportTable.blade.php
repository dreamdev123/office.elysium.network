<div>
    <div class="reportOptions" style="margin-top: 10px">
        <button type="button" class="btn btn-outline green downloadExcel"><i class="fa fa-file-excel-o"></i>{{ _mt($moduleId,'ActiveUserHistory.excel') }}</button>
    </div>
    <table class="table table-bordered table-striped table-hover dataTable  dtr-inline  reporttable" id="TotalActiveUserHistoryTable">
        @if($rankCounts)
        <thead>
        <tr>
            <th>{{ _mt($moduleId,'ActiveUserHistory.sl_no') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.package_name') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.jan') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.feb') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.march') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.april') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.may') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.june') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.july') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.august') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.september') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.october') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.november') }}</th>
            <th> {{ _mt($moduleId,'ActiveUserHistory.december') }}</th>
        </tr>
        </thead>
        <tbody>

            @foreach($rankCounts as $key=>$rankCount)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td>{{ $rankCount['name'] }}</td>
                    @foreach($rankCount['count'] as $monthlyCount)
                        <td> {{ $monthlyCount }} </td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                @foreach($totalCounts as $totalCount)
                    <td> {{ $totalCount }} </td>
                @endforeach
            </tr>
        </tbody>
        @else
            Acitve Users History is an empty
        @endif
    </table>
</div>

<script>
// Download report as Excel
    $('.downloadExcel').click(function () {
        exportTableToExcel('TotalActiveUserHistoryTable','ActiveUserHistory');
    });


    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }

</script>