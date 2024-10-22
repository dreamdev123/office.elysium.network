@extends('User.Layout.master')
@section('content')

	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}"/>

	<div class="video-library-page">
		@include('_includes.soho_nav')
		<div class="soho-network-video-section">
			<div class="newSoho-custom-container">
				<p class="network-vd-section-title">Elysium Network</p>
			</div>
			<div class="video-slider-wrappr">
				<div class="newSoho-custom-container">
					<div class="image-slider-wrapper1">
					@if (count($networkFileNames) > 0)
						@foreach($networkFileNames as $filename)
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/network')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						@endforeach
					@endif
					</div>
					<div class="image-slider-buttons">
						<div class="button-group">
							<span class="choose_brand_title">Choose your Video</span>
						</div>
					</div>
				</div>
			</div>
			<div class="soho-video-download-button">
				<button class="download-btn network-video-btn">Download</button>
			</div>
		</div>
		
		<div class="soho-network-video-section">
			<div class="newSoho-custom-container">
				<p class="network-vd-section-title">Elysium Capital</p>
			</div>
			<div class="video-slider-wrappr">
				<div class="newSoho-custom-container">
					<div class="image-slider-wrapper2">
					@if (count($capitalFileNames) > 0)
						@foreach($capitalFileNames as $filename)
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/capital')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						@endforeach
					@endif
					</div>
					<div class="image-slider-buttons">
						<div class="button-group">
							<span class="choose_brand_title">Choose your Video</span>
						</div>
					</div>
				</div>
			</div>
			<div class="soho-video-download-button">
				<button class="download-btn capital-video-btn">Download</button>
			</div>
		</div>

		<div class="soho-network-video-section">
			<div class="newSoho-custom-container">
				<p class="network-vd-section-title">Elysium Insider</p>
			</div>
			<div class="video-slider-wrappr">
				<div class="newSoho-custom-container">
					<div class="image-slider-wrapper3">
					@if (count($insiderFileNames) > 0)
						@foreach($insiderFileNames as $filename)
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/insider')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						@endforeach
					@endif
					</div>
					<div class="image-slider-buttons">
						<div class="button-group">
							<span class="choose_brand_title">Choose your Video</span>
						</div>
					</div>
				</div>
			</div>
			<div class="soho-video-download-button" style="margin-bottom: 60px;">
				<button class="download-btn insider-video-btn">Download</button>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="{{ asset('slick/slick.js') }}"></script>
	<script type="text/javascript">
		$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
		var selectedImageName = '';
		var nfileNames = '{{$nfiles}}';
        nfileNames = nfileNames.split('&quot;').join('"');
        nfileNames = JSON.parse(nfileNames);
		var cfileNames = '{{$cfiles}}';
        cfileNames = cfileNames.split('&quot;').join('"');
        cfileNames = JSON.parse(cfileNames);
		var ifileNames = '{{$ifiles}}';
        ifileNames = ifileNames.split('&quot;').join('"');
        ifileNames = JSON.parse(ifileNames);
		$(document).ready(function(){
      $('.image-slider-wrapper1').slick({
        infinite: true,
        centerMode: true,
        draggable: false,
        slidesToShow: nfileNames.length > 3 ? 3 : 1,
        slidesToScroll: 3
      });
      $('.image-slider-wrapper2').slick({
        infinite: true,
        centerMode: true,
        draggable: false,
        slidesToShow: cfileNames.length > 3 ? 3 : 1,
        slidesToScroll: 3
      });
      $('.image-slider-wrapper3').slick({
        infinite: true,
        centerMode: true,
        draggable: false,
        slidesToShow: ifileNames.length > 3 ? 3 : 1,
        slidesToScroll: 3
      });
    });

    $('.network-video-btn').on('click', function() {
    	let selectedVideoName = '';
    	let src = $('.image-slider-wrapper1').find('.slick-current').find('source').attr('src');
    	arraySrc = src.split('/');
    	if (arraySrc.length > 1) {
        selectedVideoName = arraySrc[arraySrc.length - 1];
      }
    	window.location.href = '{{ route('newsoho.downloadSohoFile') }}?type=video&brand=network&filename='+selectedVideoName;
    })

    $('.capital-video-btn').on('click', function() {
    	let selectedVideoName = '';
    	let src = $('.image-slider-wrapper2').find('.slick-current').find('source').attr('src');
    	arraySrc = src.split('/');
    	if (arraySrc.length > 1) {
        selectedVideoName = arraySrc[arraySrc.length - 1];
      }
    	window.location.href = '{{ route('newsoho.downloadSohoFile') }}?type=video&brand=capital&filename='+selectedVideoName;
    })

    $('.insider-video-btn').on('click', function() {
    	let selectedVideoName = '';
    	let src = $('.image-slider-wrapper3').find('.slick-current').find('source').attr('src');
    	arraySrc = src.split('/');
    	if (arraySrc.length > 1) {
        selectedVideoName = arraySrc[arraySrc.length - 1];
      }
    	window.location.href = '{{ route('newsoho.downloadSohoFile') }}?type=video&brand=insider&filename='+selectedVideoName;
    })
  </script>
	
@endsection