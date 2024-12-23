<form class="filterForm">
    <div class="filters row">
        <div class="eachFilter operation col-md-3">
            <label>{{ _mt($moduleId,'AccountStatus.user') }}</label>
            <input type="text" class="userFiller" placeholder="Enter user name"/>
            <input type="hidden" name="filters[user_id]" id="user_id">
        </div>
        <div class="eachFilter operation col-md-3">
            <label>{{ _mt($moduleId, 'AccountStatus.member_id') }}</label>
            <input type="text" class="memberIdFiller" placeholder="Enter Member ID"/>
            <input type="hidden" name="filters[customer_id]" id="member_id"/>
        </div>

        <div class="eachFilter operation col-md-4">
            <button type="button" class="btn dark filterRequest ladda-button" data-style="contract">
                <i class="fa fa-filter"></i>{{ _mt($moduleId,'AccountStatus.filter') }}
            </button>
            <button type="button" class="btn dark clearFilter ladda-button" data-style="contract">
                <i class="fa fa-refresh"></i>{{ _mt($moduleId, 'AccountStatus.reset') }}
            </button>
        </div>
    </div>
</form>

<script>
    'use strict';
    $(function () {
        Ladda.bind('.ladda-button');
        //User fetcher
        var selectedCallback = function (target, id, name) {
            $('.userFiller').val(name);
            $('#user_id').val(id);

        };
        var options = {
            target: '.userFiller',
            route: '{{ route("user.api") }}',
            action: 'getUsers',
            name: 'username',
            selectedCallback: selectedCallback,
            clearCallback: function (element) {
                element.siblings('input[name="filters[user_id]"]').val('')
            }
        };
        $('.userFiller').ajaxFetch(options);


        //fetch report table while filter is clicked
        $('.filterRequest').click(function () {
            fetchCurrentAccountStatusReport();
        });

        $('.clearFilter').click(function () {
            loadreportFilters();
        });

        //Member ID fetcher
        var selectedIdCallback = function (target, id, name) {

            $('.memberIdFiller').val(name);
            $('#member_id').val(name);

        };
        var options = {
            target: '.memberIdFiller',
            route: '{{ route("user.api") }}',
            action: 'getMembersId',
            name: 'customer_id',
            selectedCallback: selectedIdCallback,
            clearCallback: function (element) {
                element.siblings('input[name="filters[customer_id]"]').val('')
            }
        };
        $('.memberIdFiller').ajaxFetch(options);

    })
</script>
