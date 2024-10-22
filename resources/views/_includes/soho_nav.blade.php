@if ($user->customer_id != 526792)
<nav class="navbar newSoho-navbar">
    <div class="navbar-header newSoho-navbar-header">
      <img src="{{asset('images/SOHO.png')}}" style="width: 140px; padding: 12px;" class="padding-left"/>
    </div>
    <ul class="nav navbar-nav newSoho-navbar-nav">
      <li><a href="{{ route('user.soho')}}" class="@if (Request::url() === (route('user.soho'))) active @endif">Content Creator</a></li>
      <li><a href="{{ route('user.soho.contentLibrary')}}" class="@if (Request::url() === (route('user.soho.contentLibrary'))) active @endif">Content Library</a></li>
      <li><a href="{{ route('user.soho.videoLibrary')}}" class="@if (Request::url() === (route('user.soho.videoLibrary'))) active @endif">Video Library</a></li>
      <li><a href="{{ route('user.soho.email')}}" class="@if (Request::url() === (route('user.soho.email'))) active @endif">Tell a Friend</a></li>
    </ul>
</nav>
@else
<nav class="navbar newSoho-navbar">
    <ul class="nav navbar-nav newSoho-navbar-nav1">
      <li><a href="{{ route('user.soho')}}" class="@if (Request::url() === (route('user.soho'))) active @endif">Content Creator</a></li>
      <li><a href="{{ route('user.soho.contentLibrary')}}" class="@if (Request::url() === (route('user.soho.contentLibrary'))) active @endif">Content Library</a></li>
      <li><a href="{{ route('user.soho.videoLibrary')}}" class="@if (Request::url() === (route('user.soho.videoLibrary'))) active @endif">Video Library</a></li>
      <li><a href="{{ route('user.soho.email')}}" class="@if (Request::url() === (route('user.soho.email'))) active @endif">Tell a Friend</a></li>
      <li><a href="{{ route('user.soho.imageManagement')}}" class="@if (Request::url() === (route('user.soho.imageManagement'))) active @endif">Image management</a></li>
      <li><a href="{{ route('user.soho.videoManagement')}}" class="@if (Request::url() === (route('user.soho.videoManagement'))) active @endif">Video Management</a></li>
    </ul>
</nav>
@endif