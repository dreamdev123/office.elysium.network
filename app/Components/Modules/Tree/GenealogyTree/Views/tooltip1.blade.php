<div class="card">
    <div class="card-top">
        <div class="tooltip-photo">
            <?php if (!$profile->package || $profile->package->slug == 'affiliate'){ ?>
                <img src="{{asset('photos/Affiliate.jpeg')}}">
            <?php } elseif (!$profile->package || $profile->package->slug == 'influencer') { ?>
                <img src="{{asset('photos/influencer.png')}}">
            <?php } elseif (!$profile->package || $profile->package->slug == 'client') { ?>
                <img src="{{asset('photos/client.jpeg')}}">
            <?php } else { ?>
                <img src="{{asset('photos/IB.jpeg')}}">
            <?php } ?>
        </div>
        <div class="tooltip-name">
            {{ $profile->username }}
        </div>
    </div>
    <div class="col-sm-12 tooltip-table">
        <table id="table" class="table table-striped table-hover">
            @if(in_array('fullname', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.fullname') }}</td>
                    <td>{{ $profile->metaData->firstname }} {{ $profile->metaData->lastname }}</td>
                </tr>
            @endif
            @if(in_array('firstname', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.firstname') }}</td>
                    <td>{{!$profile->hide_name?$profile->metaData->firstname:'N/A' }}</td>
                </tr>
            @endif
            @if(in_array('lastname', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.lastname') }}</td>
                    <td>{{!$profile->hide_name?$profile->metaData->lastname:'N/A'}}</td>
                </tr>
            @endif
            @if(isset($sponsor))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.Sponsor') }}</td>
                    <td>{{ $sponsor->username }}</td>
                </tr>
            @endif
            @if(in_array('email', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.email') }}</td>
                    <td>{{ $profile->email }}</td>
                </tr>
            @endif
            @if(in_array('phone', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.phone') }}</td>
                    <td>{{ getPhoneCode($profile->metaData->country_id) }} {{ $profile->phone }}</td>
                </tr>
            @endif
            @if(in_array('joining_date', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.joining_date') }}</td>
                    <td>{{ date('Y-m-d',strtotime($profile->created_at)) }}</td>
                </tr>
            @endif
            @if(in_array('status', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.status') }}</td>
                    <td>@if($profile->expiry_date > date("Y-m-d")) Active @elseif($profile->package_id == 1 && (!isset($profile->expiry_date) || $profile->expiry_date == '0000-00-00')) Active @else Inactive @endif</td>
                </tr>
            @endif
            @if(in_array('current_rank', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.current_rank') }}</td>
                    <td>@if($profile->rank) {{ $profile->rank->rank->name }} @else NA @endif</td>
                </tr>
            @endif
            @if(in_array('position', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.position') }}</td>
                    <td>@if(($profile->repoData->position) == 1) Left @elseif(($profile->repoData->position) == 2)
                            Right @else NA @endif
                    </td>
                </tr>
            @endif
            @if(in_array('parent_user', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.parent_user') }}</td>
                    <td>{{ usernameFromId($profile->repoData->parent_id) }}</td>
                </tr>
            @endif
            @if(in_array('sponsor_user', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.sponsor_user') }}</td>
                    <td>{{ usernameFromId($profile->repoData->sponsor_id) }}</td>
                </tr>
            @endif
            @if(in_array('package', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.package') }}</td>
                    <td>{{ getPackageInfo($profile->package_id)['name'] }}</td>
                </tr>
            @endif
            @if(in_array('country', $moduleData->get('tooltip_info')))
                <tr>
                    <td>{{ _mt($moduleId,'GenealogyTree.country') }}</td>
                    <td>{{ getCountryName($profile->metaData->country_id) }}</td>
                </tr>
            @endif
            {!! defineFilter('toolTip', '', 'tree', ['userId' => $profile->id]) !!}
        </table>
    </div>
</div>

<style type="text/css">

    .card {
        min-width: 375px;
    }

    .card-top {
        background-color: #40b7e5;
    }

    .tooltip-name {
        color:  #fff;
    }
</style>