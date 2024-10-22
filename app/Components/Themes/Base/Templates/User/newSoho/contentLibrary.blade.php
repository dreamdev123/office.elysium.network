@extends('User.Layout.master')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}"/>

	<div class="content-library-page">
		@include('_includes.soho_nav')
		
		<div class="newSoho-custom-container">
			<div class="choose-brand-section">
				<div class="row radio-group-section">
					<div class="col-md-3 choose_brand_title">
						Choose Your Brand
					</div>
					<div class="col-md-9 select-option">
						<div class="radio-list">
							<label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="network" data-title="Elysium Network" {{ $brand == 'network' ? 'checked' : '' }} />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Network</span>
	            </label>
	            <label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="capital" data-title="Elysium Capital" {{ $brand == 'capital' ? 'checked' : '' }} />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Capital</span>
	            </label>
	            <label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="insider" data-title="Elysium Insider" {{ $brand == 'insider' ? 'checked' : '' }} />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Insider</span>
	            </label>
	          </div>
					</div>
				</div>
			</div>
		</div>
		<div class="choose-image-section">
			<div class="newSoho-custom-container">
				<div class="image-slider-wrapper">
					@if (count($fileNames) > 0)
						@foreach($fileNames as $filename)
							@foreach($filename as $key=>$name)
							<div class="each-panel">
								@if($key == 'images')
								<img src="{{ asset('uploads/images') }}/{{ $user->customer_id }}/{{ $brand }}/{{ $name }}" data-location="{{ $key }}">
								@else
								<img src="{{ asset('uploads/library') }}/{{ $brand }}/{{ $name }}" data-location="{{ $key }}">
								@endif
							</div>
							@endforeach
						@endforeach
					@endif
				</div>
				<div class="image-slider-buttons">
					<div class="button-group">
						<span class="choose_brand_title">Choose your Image</span>
					</div>
				</div>
			</div>
		</div>

		<div class="newSoho-custom-container">
			<div class="content-library-img-preview-section">
				<p class="title">Content Preview</p>
				@if (count($fileNames) > 0)
					@foreach($fileNames[0] as $key=>$name)
					<div class="img-preview">
						@if($key == 'images')
						<img class="preview-img" src="{{ asset('uploads/images') }}/{{ $user->customer_id }}/{{ $brand }}/{{ $name }}">
						@else
						<img class="preview-img" src="{{ asset('uploads/library') }}/{{ $brand }}/{{ $name }}">
						@endif
					</div>
					@endforeach
				@endif
				<button class="download-btn">Download</button>
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
		var fileNames = '{{$files}}';
        fileNames = fileNames.split('&quot;').join('"');
        fileNames = JSON.parse(fileNames);
		$(document).ready(function(){
      $('.image-slider-wrapper').slick({
        infinite: true,
        centerMode: true,
        draggable: false,
        slidesToShow: fileNames.length > 5 ? 5 : fileNames.length > 3 ? 3 : 1,
        slidesToScroll: 3
      });
    });

    $(document).on('click', '.slick-arrow', function() {
      let src = $('.slick-current').find('img').attr('src');
      $('.preview-img').attr('src', src);
    })

    $('.brand-group').change(function(){
      if( $(this).is(":checked") ) {
        var brand = $(this).val();
        window.location.href = '{{ route('newsoho.contentLibrary') }}?brand=' + brand;
      }
    });

    $('.download-btn').on('click', function() {
    	let selectedImageName = '';
    	let src = $('.slick-current').find('img').attr('src');
    	let location = $('.slick-current').find('img').attr('data-location');
    	arraySrc = src.split('/');
    	if (arraySrc.length > 1) {
        selectedImageName = arraySrc[arraySrc.length - 1];
      }
    	window.location.href = '{{ route('newsoho.downloadSohoFile') }}?type=image&brand=' + '{{ $brand }}&location=' + location + '&filename=' + selectedImageName;
    })

	</script>
@endsection