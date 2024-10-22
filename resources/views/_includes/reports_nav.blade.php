
<div class="row personalInfoGridWrapper rows">
    @php
    $user = loggedUser();
    @endphp
    @if ($user->package->slug != 'affiliate' && $user->package->slug != 'client')
    <div class="cols">
        <a href="{{route('user.report')}}" class="infoGrid @if (Request::url() === (route('user.report'))) active @endif">
            <h3>Annual</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.report.binaryPoint') }}" class="infoGrid @if (Request::url() === (route('user.report.binaryPoint'))) active @endif">
            <h3>CV Points</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.report.ReferralList.myReferral') }}" class="infoGrid @if (Request::url() === (route('user.report.ReferralList.myReferral'))) active @endif">
            <h3>Referral List</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.clientReport.index') }}" class="infoGrid @if (Request::url() === (route('user.clientReport.index'))) active @endif">
            <h3>Equiti Client</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.multiBankClientReport.index') }}" class="infoGrid @if (Request::url() === (route('user.multiBankClientReport.index'))) active @endif">
            <h3>MultiBank Client</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.report.earning') }}" class="infoGrid @if (Request::url() === (route('user.report.earning'))) active @endif">
            <h3>Earning Report</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.report.activity') }}" class="infoGrid @if (Request::url() === (route('user.report.activity'))) active @endif">
            <h3>Activity</h3>
        </a>
    </div>
    @else
    <div class="cols">
        <a href="{{route('user.report')}}" class="infoGrid @if (Request::url() === (route('user.report'))) active @endif">
            <h3>Annual</h3>
        </a>
    </div>
    <div class="cols">
        <a href="javascript:;" class="infoGrid nav-affiliate">
            <h3>CV Points</h3>
        </a>
    </div>
    <div class="cols">
        <a href="javascript:;" class="infoGrid nav-affiliate">
            <h3>Referral List</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.clientReport.index') }}" class="infoGrid @if (Request::url() === (route('user.clientReport.index'))) active @endif">
            <h3>Equiti Client</h3>
        </a>
    </div>
    <div class="cols">
        <a href="{{ route('user.multiBankClientReport.index') }}" class="infoGrid @if (Request::url() === (route('user.multiBankClientReport.index'))) active @endif">
            <h3>MultiBank Client</h3>
        </a>
    </div>
    <div class="cols">
        <a href="javascript:;" class="infoGrid nav-affiliate">
            <h3>Earning Report</h3>
        </a>
    </div>
    <div class="cols">
        <a href="javascript:;" class="infoGrid nav-affiliate">
            <h3>Activity</h3>
        </a>
    </div>
    @endif
</div>
<style type="text/css">
    .cols {
        width: 14.25%;
        padding: 0 1px;
    }
    .rows {
        display: flex;
        flex-wrap: wrap;
    }
</style>