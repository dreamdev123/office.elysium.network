@php
    $user = loggedUser();
@endphp
<div class="row personalInfoGridWrapper">
    <div class="col-sm-2">
         <a href="{{ route('user.mail')}}" class="infoGrid @if (Request::url() === (route('user.mail'))) active @endif">
            <h3>Email List</h3>
        </a>
    </div>
    @if ($user->customer_id != 888888)
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
    @else
    <div class="col-sm-2">
        <a href="{{ route('user.email.broadcast.index') }}" class="infoGrid @if (Request::url() === (route('user.email.broadcast.index'))) active @endif">
            <h3>Email Broadcasting</h3>
        </a>
    </div>
    @endif
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
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
</div>