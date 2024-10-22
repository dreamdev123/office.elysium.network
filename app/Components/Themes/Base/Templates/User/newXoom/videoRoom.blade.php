@extends('User.Layout.master')
@section('content')
	@if(getScope()=='user')
    @include('_includes.xoom_nav')
    <div class="heading" style="margin-top: 50px">
    </div>
  @endif()
  @if($user->username)
  <script type="text/javascript">
    const own_username = '{{ $user->username }}';
  </script>
  @endif
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css') }}"> -->
	<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}"/>
	<script type="text/javascript" src="{{ asset('slick/slick.js') }}"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
  <script src="{{ asset('js/adapter.min.js') }}"></script>
  <script src="{{ asset('js/janus.js') }}"></script>
  <script src="{{ asset('js/mvideoroomtest.js') }}"></script>
  <style type="text/css">
  	.margin-bottom-sm {
  		margin-bottom: 5px;
  	}
  </style>

  <div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Video Room
					<button class="btn btn-default" autocomplete="off" id="start">Start</button>
				</h1>
			</div>
			<div class="" id="details">
				<div class="row">
					<div class="col-md-12">
						<h3>Video Room details</h3>
						<p>To use the Video Room, just insert a username to join the default room that is configured. This will add you to the list of participants, and allow you to automatically send your audio/video frames and receive the other participants' feeds. The other participants will appear in separate panels, whose titles will be the names they chose when registering at the Video Room.</p>
						<p>Press the <code>Start</code> button above to launch the Video Room.</p>
					</div>
				</div>
			</div>
			<div class="hide" id="videojoin">
				<div class="row">
					<span class="label label-info" id="you"></span>
					<div class="col-md-12" id="controls">
						<div class="input-group margin-bottom-md hide" id="registernow">
							<span class="input-group-addon">@</span>
							<input autocomplete="off" class="form-control" type="text" placeholder="Choose a display name" id="username" onkeypress="return checkEnter(this, event);">
							<span class="input-group-btn">
								<button class="btn btn-success" autocomplete="off" id="register">Join the room</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="hide" id="videos">
				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Local Video <span class="label label-primary hide" id="publisher"></span>
									<div class="btn-group btn-group-xs pull-right hide">
										<div class="btn-group btn-group-xs">
											<button id="bitrateset" autocomplete="off" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
												Bandwidth<span class="caret"></span>
											</button>
											<ul id="bitrate" class="dropdown-menu" role="menu">
												<li><a href="#" id="0">No limit</a></li>
												<li><a href="#" id="128">Cap to 128kbit</a></li>
												<li><a href="#" id="256">Cap to 256kbit</a></li>
												<li><a href="#" id="512">Cap to 512kbit</a></li>
												<li><a href="#" id="1024">Cap to 1mbit</a></li>
												<li><a href="#" id="1500">Cap to 1.5mbit</a></li>
												<li><a href="#" id="2000">Cap to 2mbit</a></li>
											</ul>
										</div>
									</div>
								</h3>
							</div>
							<div class="panel-body" id="videolocal"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Remote Video #1 <span class="label label-info hide" id="remote1"></span></h3>
							</div>
							<div class="panel-body relative" id="videoremote1"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Remote Video #2 <span class="label label-info hide" id="remote2"></span></h3>
							</div>
							<div class="panel-body relative" id="videoremote2"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Remote Video #3 <span class="label label-info hide" id="remote3"></span></h3>
							</div>
							<div class="panel-body relative" id="videoremote3"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Remote Video #4 <span class="label label-info hide" id="remote4"></span></h3>
							</div>
							<div class="panel-body relative" id="videoremote4"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Remote Video #5 <span class="label label-info hide" id="remote5"></span></h3>
							</div>
							<div class="panel-body relative" id="videoremote5"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

	</script>
@endsection