<!-- <nav class="navbar newSoho-navbar">
    <div class="navbar-header newSoho-navbar-header">
      <img src="{{asset('images/XOOM.png')}}" style="width: 140px; padding: 12px;" class="padding-left"/>
    </div>
    <ul class="nav navbar-nav newSoho-navbar-nav">
      <li><a href="javascript:;" class="active">
        <h3>Video Call</h3></a></li>
      <li><a href="javascript:;" class="">
        <h3></h3>
      </a></li>
      <li><a href="javascript:;" class="">
        <h3></h3>
      </a></li>
      <li><a href="javascript:;" class="">
        <h3></h3>
      </a></li>
    </ul>
</nav> -->


<div class="row personalInfoGridWrapper">
    <div class="col-sm-2">
         <a href="{{ route('user.newxoom')}}" class="infoGrid @if (Request::url() === (route('user.newxoom'))) active @endif">
            <h3>Video Call</h3>
        </a>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('user.newxoom.videoRoom')}}" class="infoGrid @if (Request::url() === (route('user.newxoom.videoRoom'))) active @endif">
            <h3>Video Room</h3>
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
    <div class="col-sm-2">
        <a class="infoGrid">
            <h3></h3>
        </a>
    </div>
</div>