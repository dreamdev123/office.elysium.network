@if($commissionData->count())
    <div class="panel-body table-responsive">
        <table class="table">
            <tbody>
            @foreach($commissionData as $commission)
                <tr>
                    <td>{{ date('Y-m-d',strtotime($commission->created_at)) }}</td>
                    <td><strong><span>€</span> {{ $commission->amount }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="paginationWrapper">
            {!! $commissionData->links() !!}
        </div>
    </div>
@else
    <div style="text-align: center">
        <h5> No Commission Found </h5>
    </div>
@endif

<script>
    $('.paginationWrapper .pagination li a').click(function (e) {
        e.preventDefault();
        var route = $(this).attr('href');
        let commissionId = $(this).closest('.earningsGrid').find('.showCommissionTable').data('id');
        let commissionArea = $(this).closest('.earningsGrid').find('.showCommissionTable').data('code');
        loadCommissionTable(commissionId, commissionArea, route);
    });
</script>