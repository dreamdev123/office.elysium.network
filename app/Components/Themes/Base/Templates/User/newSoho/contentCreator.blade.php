@extends('User.Layout.master')
@section('content')

	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}"/>

	<div class="content-creator-page">
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
						<div class="each-panel">
							<img src="{{ asset('uploads/samples') }}/{{ $brand }}/{{ $filename }}">
						</div>
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
			<div class="soho-image-edit-section">
				<div class="preview-section">
					<p class="title">Content Preview</p>
					@if (count($fileNames) > 0)
					<div class="preview-img-section">
						<img class="preview-img" src="{{ asset('uploads/samples') }}/{{ $brand }}/{{ $fileNames[0] }}">
						<div id="create-message-preview"></div>
						<span id="copyright-preview">THIS CONTENT IS CREATED BY MEMBER {{ $user->customer_id }}</span>
					</div>
					@endif
				</div>
				<div class="edit-option-section">
					<p class="title">Create Your Message</p>
					<div class="image-caption-input" contenteditable="true"></div>
					<div class="row radio-group-section">
						<div class="col-md-3 choose_brand_title">
							Type
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
								<label class="radio-container">
	                <input type="radio" class="fontweight-group" name="fontWeight" value="regular" data-title="Regular" checked />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Regular</span>
		            </label>
		          </div>
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
		            <label class="radio-container">
	                <input type="radio" class="fontweight-group" name="fontWeight" value="medium" data-title="Medium" />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Medium</span>
		            </label>
		          </div>
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
		            <label class="radio-container">
	                <input type="radio" class="fontweight-group" name="fontWeight" value="bold" data-title="Bold" />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Bold</span>
		            </label>
		          </div>
						</div>
					</div>
					<div class="row radio-group-section">
						<div class="col-md-3 choose_brand_title">
							Alignment
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
								<label class="radio-container">
	                <input type="radio" class="aligntype-group" name="alignType" value="left" data-title="Left" checked />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Left</span>
		            </label>
		          </div>
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
		            <label class="radio-container">
	                <input type="radio" class="aligntype-group" name="alignType" value="center" data-title="Center" />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Center</span>
		            </label>
		          </div>
						</div>
						<div class="col-md-3 select-option">
							<div class="radio-list">
		            <label class="radio-container">
	                <input type="radio" class="aligntype-group" name="alignType" value="right" data-title="Right" />
	                <span class="checkbox-circle"></span>
	                <span class="checkbox-name">Right</span>
		            </label>
		          </div>
						</div>
					</div>
					<p class="soho-fb-share-title">Insert your facebook link for publishing</p>
					<input type="text" class="soho-fb-share-link-input" name="" placeholder="https://www.facebook.com/yourname">

					<div class="publish-button-group">
						<button class="download-btn" {{count($fileNames) < 1 ? 'disabled' : ''}}>Download</button>
						<button class="publish-btn" {{count($fileNames) < 1 ? 'disabled' : ''}}>Publish</button>
					</div>
				</div>
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
    	var fontweight = $("input[type='radio'].fontweight-group:checked").val();
    	if (fontweight == 'regular') {
  			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Regular', sans-serif");
  			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Regular', sans-serif");
  		} else if (fontweight == 'medium') {
  			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Medium', sans-serif");
  			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Medium', sans-serif");
  		} else if (fontweight == 'bold') {
  			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Bold', sans-serif");
  			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Bold', sans-serif");
  		}
    	var aligntype = $("input[type='radio'].aligntype-group:checked").val();
    	if (aligntype == 'left') {
  			$('.image-caption-input').css("text-align", "left");
  			$('#create-message-preview').css("text-align", "left");
  		} else if (aligntype == 'center') {
  			$('.image-caption-input').css("text-align", "center");
  			$('#create-message-preview').css("text-align", "center");
  		} else if (aligntype == 'right') {
  			$('.image-caption-input').css("text-align", "right");
  			$('#create-message-preview').css("text-align", "right");
  		}
    });

    $(document).on('click', '.slick-arrow', function() {
    	let src = $('.slick-current').find('img').attr('src');
    	$('.preview-img').attr('src', src);
    })

    $('.brand-group').change(function(){
      if( $(this).is(":checked") ) {
        var brand = $(this).val();
        window.location.href = '{{ route('newsoho') }}?brand=' + brand;
      }
    });

    $('.image-caption-input').on('keyup', function() {
    	var content = $('.image-caption-input').html();
    	$('#create-message-preview').html(content);
    })

    $('.fontweight-group').change(function() {
    	if ($(this).is(":checked")) {
    		var val = $(this).val();
    		if (val == 'regular') {
    			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Regular', sans-serif");
    			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Regular', sans-serif");
    		} else if (val == 'medium') {
    			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Medium', sans-serif");
    			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Medium', sans-serif");
    		} else if (val == 'bold') {
    			$('.image-caption-input').css("font-family", "'DIN Pro Condensed Bold', sans-serif");
    			$('#create-message-preview').css("font-family", "'DIN Pro Condensed Bold', sans-serif");
    		}
    	}
    })

    $('.aligntype-group').change(function() {
    	if ($(this).is(":checked")) {
    		var val = $(this).val();
    		if (val == 'left') {
    			$('.image-caption-input').css("text-align", "left");
    			$('#create-message-preview').css("text-align", "left");
    		} else if (val == 'center') {
    			$('.image-caption-input').css("text-align", "center");
    			$('#create-message-preview').css("text-align", "center");
    		} else if (val == 'right') {
    			$('.image-caption-input').css("text-align", "right");
    			$('#create-message-preview').css("text-align", "right");
    		}
    	}
    })

    $('.download-btn').on('click', function() {
      let selectedImageName = '';
      let src = $('.slick-current').find('img').attr('src');
      arraySrc = src.split('/');
      if (arraySrc.length > 1) {
        selectedImageName = arraySrc[arraySrc.length - 1];
      }
    	var brand = $("input[type='radio'].brand-group:checked").val();
    	var fontweight = $("input[type='radio'].fontweight-group:checked").val();
    	var aligntype = $("input[type='radio'].aligntype-group:checked").val();
    	var content = $('.image-caption-input').html();
      let send_data = {};
      send_data['backgroundImage'] = selectedImageName;
      send_data['brand'] = brand;
      send_data['fontweight'] = fontweight;
      send_data['aligntype'] = aligntype;
      send_data['content'] = content;
      $.ajax({
        type: 'POST',
        url: '{{ route('newsoho.makeSohoImage') }}',
        data: send_data,
        success: function(data) {
          window.location.href = '{{ route('newsoho.downloadSohoFile') }}?type=image&location=images&brand='+brand+'&filename='+data.filename;
        },
        error: function(data){
          console.log(data);
        }
      })
    })

    $('.publish-btn').on('click', function() {
      let selectedImageName = '';
      let src = $('.slick-current').find('img').attr('src');
      arraySrc = src.split('/');
      if (arraySrc.length > 1) {
        selectedImageName = arraySrc[arraySrc.length - 1];
      }
    	var brand = $("input[type='radio'].brand-group:checked").val();
    	var fontweight = $("input[type='radio'].fontweight-group:checked").val();
    	var aligntype = $("input[type='radio'].aligntype-group:checked").val();
    	var content = $('.image-caption-input').html();
      let send_data = {};
      send_data['backgroundImage'] = selectedImageName;
      send_data['brand'] = brand;
      send_data['fontweight'] = fontweight;
      send_data['aligntype'] = aligntype;
      send_data['content'] = content;
      $.ajax({
        type: 'POST',
        url: '{{ route('newsoho.makeSohoImage') }}',
        data: send_data,
        success: function(data) {
          window.location.href = '{{ route('newsoho.shareImage') }}?brand='+brand+'&filename='+data.filename;
          // window.open('{{ route('newsoho.shareImage') }}?brand='+brand+'&filename='+data.filename, '_blank');
        },
        error: function(data){
          console.log(data);
        }
      })
    })

	</script>
@endsection