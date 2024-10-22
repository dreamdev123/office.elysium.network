@if($commissionData->count())
    <div class="panel-body table-responsive">
        <table class="table">
            <tbody>
            @foreach($commissionData as $commission)
                @php
                    $InvestmentId = str_replace('Investment ID : ', '', $commission->operation->remarks);
                    $client = \App\Components\Modules\Commission\PerformanceFeeCommission\ModuleCore\Eloquents\InvestmentRoi::find($InvestmentId)->client->capital_user_id;
                    $capital_user = \App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\CapitalUser::find($client)->client_id;
                @endphp
                <tr>
                    <td>{{ date('Y-m-d',strtotime($commission->created_at)) }}</td>
<!--                     <td>{{ $commission->commission->referenceUser->metaData->firstname }} {{$commission->commission->referenceUser->metaData->lastname }}</td> -->
                    <td>{{ $capital_user }}</td>
                    <td>{{ $commission->commission->distribute_amount?'€ '.$commission->commission->distribute_amount:'' }}</td>
                    {{--<td>LEVEL <strong>3</strong></td>--}}
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