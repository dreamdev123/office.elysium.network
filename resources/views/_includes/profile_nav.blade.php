<div class="row personalInfoGridWrapper">
    <div class="col-sm-2">
         <a href="{{ route('user.personalinfo')}}" class="infoGrid @if (Request::url() === (route('user.personalinfo'))) active @endif">
            <h3>Account Information</h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('user.expirepayment') }}" class="infoGrid @if (Request::url() === (route('user.expirepayment'))) active @endif">
        <!-- <a href="javascript:;" class="infoGrid show-subscription-modal @if (Request::url() === (route('user.expirepayment'))) active @endif"> -->
            <h3>Subscription</h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('user.my_receipt') }}" class="infoGrid @if (Request::url() === (route('user.my_receipt'))) active @endif">
            <h3>My Receipt</h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
</div>