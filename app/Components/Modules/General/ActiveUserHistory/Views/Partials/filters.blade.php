<form class="filterForm" >
    <div class="row">
        <div class="eachFilter operation col-md-3">
            <label>{{ _mt($moduleId, 'ActiveUserHistory.country') }}</label>
            <select name="filters[country_ids][]" id="country_ids" class="form-control"  multiple="multiple" >
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filters">
            <div class="eachFilter operation col-md-2">
                <label>{{ _mt($moduleId, 'ActiveUserHistory.Status') }}</label>
                <select name="filters[status]" id="status" class="form-control" size="1">
                    <option value="1"> {{ _mt($moduleId, 'ActiveUserHistory.active_only') }}</option>
                    <option value="0"> {{ _mt($moduleId, 'ActiveUserHistory.inactive') }}</option>
                </select>
            </div>
            <div class="eachFilter operation col-md-2">
                <label>{{ _mt($moduleId, 'ActiveUserHistory.Year') }}</label>
                <select name="filters[year]" id="year" class="form-control" size="1">
                    @for($year = $currentYear; $year >= 2021; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="eachFilter operation col-md-3">
                <button type="button" class="btn dark filterRequest ladda-button" data-style="contract">
                    <i class="fa fa-filter"></i>{{ _mt($moduleId,'ActiveUserHistory.filter') }}
                </button>
                <button type="button" class="btn dark clearFilter ladda-button" data-style="contract">
                    <i class="fa fa-filter"></i>{{ _mt($moduleId,'ActiveUserHistory.reset') }}
                </button>
            </div>
        </div>
    </div>

</form>

<script>
    'use strict';

    $(function () {

        Ladda.bind('.ladda-button');
        $('#country_ids').select2({
            // multiple:true,
            placeholder: "Select a country",
            // allowClear: true
        });
        //fetch report table while filter is clicked
        $('.filterRequest').click(function () {
            fetchTotalRankSummary();
        });
        $('.clearFilter').click(function () {
            loadreportFilters();
        });
    });
</script>