<div class="portlet light portlet-fit userListPortlet">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-directions font-green hide"></i>
            <span class="caption-subject bold font-dark uppercase ">{{ _t('index.recent_activities') }}</span>
            <span class="caption-helper">{{ _t('index.showing_last_10') }}</span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="activitiesWrapper">
            @forelse($activities as $history)
                @if (!$history->activity)
                    @continue
                @endif
                <div class="eachActivity">
                    <div class="activityHeader">
                        @if($history->user_id)
                            <div class="profilePic">
                                <img src="{{ getProfilePic($history->user) }}">
                            </div>
                        @endif
                        <span class="timestamp">{{ $history->created_at->format('H:i A') }}</span>
                    </div>
                    <div class="activityCircleHolder">
                    <span class="activityCircle"
                          @if($history->activity->color) style="border-color: {{ $history->activity->color }}" @endif>
                        <i class="{{ $history->activity->icon }}"></i>
                    </span>
                    </div>
                    <div class="activityBody">
                        {{ str_replace('%user%', $history->user->username, $history->activity->description) }}
                        <span class="timestamp">{{ $history->created_at->format('D Y H:i A') }}
                            <span>({{ $history->created_at->diffForHumans() }})</span></span>
                    </div>
                </div>
            @empty
                <div class="noActivity">{{ _t('index.there_are_no_activities') }}</div>
            @endforelse
        </div>
    </div>
</div>
<script>
    "use strict";

    $(function () {
        let check = setInterval(function () {
            if ($('.userDetailsHolder').length) {
                clearInterval(check);
                @if($activities->count() > 5)
                $('.activitiesWrapper').slimScroll({height: $('.userListPane.latestJoinings').outerHeight() - $('.activitiesHolder .portlet-title').outerHeight() + 'px'});
                @endif
            }
        }, 1000);
    });
</script>
