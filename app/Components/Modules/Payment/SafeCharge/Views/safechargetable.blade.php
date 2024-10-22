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

<div class="referralListWrapper">
    <div class="referralListContainer">
        @if(count($downlines) > 0)
            {{--<div class="table-scrollable">--}}
                <div id="table-scroll" class="table-scroll">
                    <div id="faux-table" class="faux-table" aria="hidden"></div>
                    <div id="table-wrap" class="table-wrap">
                        <table class="table table-striped table-advance table-hover" id="referral-table">
                        <thead>
                        <tr>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.Sl_No') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.ProductId') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.username') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.Email') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.context') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.amount') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.date') }}</b></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($downlines as $downline)
                            <tr>
                                <td>{{$downline->id}}</td>
                                <td>{{$downline->address}}</td>
                                <td>{{  isset($downline->user['username'])?$downline->user['username']:$downline->user->username}}</td>
                                <td>{{isset($downline->user['email'])?$downline->user['email']:$downline->user->email}}</td>
                                <td>{{$downline->context}}</td>
                                <td>{{$downline->amount}}</td>
                                <td>{{ $downline->created_at}}</td>
                                <td>
                                    @if(!$downline->paid)
                                        <span class="btn btn-info btn-paid" attr_address="{{$downline->address}}"> {{ _mt('Payment-SafeCharge','SafeTransaction.paid') }}</span>
                                    @else
                                        {{ _mt('Payment-SafeCharge','SafeTransaction.already_paid') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            {{--</div>--}}
        @else
            {{ _mt('Payment-SafeCharge','SafeTransaction.noSafeTransaction') }}
        @endif
    </div>
</div>

<script>
    (function() {
        var mainTable = document.getElementById("referral-table");
        var tableHeight = mainTable.offsetHeight;
        if (tableHeight > 400) {
            var fauxTable = document.getElementById("faux-table");
            document.getElementById("table-wrap").className += ' ' + 'fixedON';
            var clonedElement = mainTable.cloneNode(true);
            clonedElement.id = "";
            fauxTable.appendChild(clonedElement);
        }

        $('.btn-paid').click(function(){
            var address = $(this).attr('attr_address');
            var self = this;
            $.ajax({
                url:'{{route("SafeCharge.paymentuser")}}',
                method:'POST',
                data:{address:address},
                success:function(res){
                    $(self).parent().html('Already Paid');
                }
            })
        })
    })();
</script>