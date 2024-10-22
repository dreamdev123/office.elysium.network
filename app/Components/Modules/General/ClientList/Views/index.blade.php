<div class="heading">
    <h3>{{ _mt('General-ClientList','ClientList.Client_List') }}</h3>
</div>
<div class="clientListWrapper" data-user="{{ $user->id }}">
    <div class="clientListContainer">

    </div>
</div>
<script>"use strict";

    $('body').on('click', '.paginationWrapper .pagination li a', function (e) {
        e.preventDefault();
        var route = $(this).attr('href');
        loadRefferalList(route);
    });
    $(function () {
        loadRefferalList();
    });

    function loadRefferalList(route) {
        simulateLoading('.clientListContainer');
        route = route ? route : '{{ route(strtolower(getScope()).'.ClientList.list') }}';
        var id = '{{ $user->id }}';
        var options = {id: id}
        $.post(route, options, function (response) {
            $('.clientListContainer').html(response);
        });
    }
</script>

