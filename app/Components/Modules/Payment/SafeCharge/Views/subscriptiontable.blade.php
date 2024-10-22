
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="referralListWrapper">
    <div class="referralListContainer">
        @if(count($subscription) > 0)
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
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.customer_id') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.date') }}</b></th>
                            <th><b>{{ _mt('Payment-SafeCharge','SafeTransaction.expiry') }}</b></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subscription as $sub)
                            <tr>
                                <td>{{$sub->id}}</td>
                                <td>{{$sub->address}}</td>
                                <td>{{ $sub->user->username}}</td>
                                <td>{{$sub->user->email}}</td>
                                <td>{{$sub->user->customer_id}}</td>
                                <td>{{ $sub->created_at}}</td>
                                <td>{{ $sub->user->expiry_date}}</td>
                                <td>
                                    @if (!$sub->status || $sub->user->expiry_date < date('Y-m-d',strtotime(date('Y-m-d') . ' + 7days')))
                                        <span class="btn btn-info btn-paid" attr_id="<?php echo $sub->id;?>">Subscribe</span>
                                    @else
                                        Subscribed
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
<script type="text/javascript" src="{{asset('js/datatable.js')}}"></script>
<script>
    (function() {
        // var mainTable = document.getElementById("referral-table");
        // var tableHeight = mainTable.offsetHeight;
        // if (tableHeight > 400) {
        //     var fauxTable = document.getElementById("faux-table");
        //     document.getElementById("table-wrap").className += ' ' + 'fixedON';
        //     var clonedElement = mainTable.cloneNode(true);
        //     clonedElement.id = "";
        //     fauxTable.appendChild(clonedElement);
        // }
        var t = $('#referral-table').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        } );  

        $(document).on('click','.btn-paid',function(){
            var address = $(this).attr('attr_id');
            var self = this;
            var row = t.row($(this).parents('tr').eq(0));
            var data = row.data();
            if(confirm('You want to subscribe this user?'))
            {
                $.ajax({
                    url:'{{route("SafeCharge.subscript_user")}}',
                    method:'POST',
                    data:{address:address},
                    success:function(res){
                        alert('You have successfully subscribe this user');    
                        data[6] = res;
                        data[7] = 'Subscribed';
                        row.data(data);
                        row.draw();
                    }
                })    
            }
        })
        
    })();
</script>