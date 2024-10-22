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
  <script src="{{ asset('js/videocalltest.js') }}"></script>
  <style type="text/css">
  	.margin-bottom-sm {
  		margin-bottom: 5px;
  	}
  	.start-btn {
  		text-transform: uppercase;
			font-size: 20px;
			color: #FFFFFF;
			background-color: #d22630;
			border: unset;
			border-radius: 0;
			padding: 5px 30px;
			margin-left: 30px;
  	}
  	.reg-call-btn {
  		color: #FFFFFF;
			background-color: #364150;
			border-color: #364150;
  		text-transform: uppercase;
			border: unset;
			border-radius: 0;
  	}
  </style>

  <div class="row">
  	<div class="col-md-12">
			<div class="page-header">
				<h1>Video Call
					<button class="btn btn-default start-btn" autocomplete="off" id="start">Start</button>
				</h1>
			</div>
			<div id="details">
				<div class="row">
					<div class="col-md-12">
						<h3>Video Call details</h3>
						<p>Just choose a simple username to register at the plugin, and then either call another user (provided you know which username was picked) or share your username with a friend and wait for a call.</p>
						<p>Press the <code>Start</code> button above to launch the Video Call.</p>
					</div>
				</div>
			</div>
			<div class="hide" id="videocall">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6 container hide" id="login">
							<div class="input-group margin-bottom-sm">
								<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
								<input class="form-control" type="text" placeholder="Enter your username" autocomplete="off" id="username" onkeypress="return checkEnter(this, event);">
							</div>
							<button class="btn btn-success margin-bottom-sm reg-call-btn" autocomplete="off" id="register">Register</button> <span class="hide label label-info" id="youok"></span>
						</div>
						<div class="col-md-6 container hide" id="phone">
							<div class="input-group margin-bottom-sm">
								<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
								<input class="form-control" type="text" placeholder="Who should we call?" autocomplete="off" id="peer" onkeypress="return checkEnter(this, event);">
							</div>
							<button class="btn btn-success margin-bottom-sm reg-call-btn" autocomplete="off" id="call">Call</button>
						</div>
					</div>
					<div class="col-md-12">
						<div id="videos" class="hide">
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Local Stream
											<div class="btn-group btn-group-xs pull-right hide">
												<button class="btn btn-danger" autocomplete="off" id="toggleaudio">Disable audio</button>
												<button class="btn btn-danger" autocomplete="off" id="togglevideo">Disable video</button>
											</div>
										</h3>
									</div>
									<div class="panel-body" id="videoleft"></div>
								</div>
								<!-- <div class="input-group margin-bottom-sm">
									<span class="input-group-addon"><i class="fa fa-cloud-upload fa-fw"></i></span>
									<input class="form-control" type="text" placeholder="Write a DataChannel message to your peer" autocomplete="off" id="datasend" onkeypress="return checkEnter(this, event);" disabled="">
								</div> -->
							</div>
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Remote Stream <span class="label label-info hide" id="callee"></span> <span class="label label-primary hide" id="curres"></span> <span class="label label-info hide" id="curbitrate"></span></h3>
									</div>
									<div class="panel-body" id="videoright"></div>
								</div>
								<!-- <div class="input-group margin-bottom-sm">
									<span class="input-group-addon"><i class="fa fa-cloud-download fa-fw"></i></span>
									<input class="form-control" type="text" id="datarecv" disabled="">
								</div> -->
							</div>
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