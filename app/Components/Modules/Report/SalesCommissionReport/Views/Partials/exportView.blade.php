@include('Report.JoiningReport.Views.Partials.reportHeader')
@if($salesData->count())
    <table style="width: 100%;">
        <thead>
        <tr>
            <th>{{ _mt($moduleId,'salesCommissionReport.sl_no') }}</th>
            <th> {{ _mt($moduleId,'salesCommissionReport.user') }}</th>
            <th> {{ _mt($moduleId,'salesCommissionReport.package') }}</th>
            <th> {{ _mt($moduleId,'salesCommissionReport.total') }}</th>
            <th> {{ _mt($moduleId,'salesCommissionReport.date') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($salesData as $sale)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ usernameFromId($sale->user_id) }} </td>
                <td>
                    @foreach($sale->products as $product)
                        {{  $product->package ? $product->package->name : '' }}
                    @endforeach
                </td>
                <td> {{ $sale->subtotal }} </td>
                <td> {{ $sale->created_at }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    {{ _mt($moduleId,'salesCommissionReport.no_sale_available') }}
@endif
