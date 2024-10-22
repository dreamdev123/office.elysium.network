@extends('User.Layout.master')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/dropzone570/dist/dropzone.css') }}">
	<script src="{{ asset('vendor/dropzone570/dist/dropzone.js') }}"></script>
	<script src="{{ asset('vendor/dropzone570/dist/dropzone-amd-module.js') }}"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<div class="content-creator-page">
		@include('_includes.soho_nav')
		<div class="newSoho-custom-container">
			<div class="file-upload-dropzone-custom-form">
				<p>Upload Videos</p>
		    <div class="newSoho-custom-container">
					<div class="choose-brand-section" style="padding-top: 5px; padding-bottom: 5px;">
						<div class="row radio-group-section">
							<div class="col-md-3 choose_brand_title">
								Choose Your Brand
							</div>
							<div class="col-md-9 select-option">
								<div class="radio-list">
									<label class="radio-container">
		                <input type="radio" class="brand-group" name="brand" value="network" data-title="Elysium Network" checked />
		                <span class="checkbox-circle"></span>
		                <span class="checkbox-name">Elysium Network</span>
			            </label>
			            <label class="radio-container">
		                <input type="radio" class="brand-group" name="brand" value="capital" data-title="Elysium Capital" />
		                <span class="checkbox-circle"></span>
		                <span class="checkbox-name">Elysium Capital</span>
			            </label>
			            <label class="radio-container">
		                <input type="radio" class="brand-group" name="brand" value="insider" data-title="Elysium Insider" />
		                <span class="checkbox-circle"></span>
		                <span class="checkbox-name">Elysium Insider</span>
			            </label>
			          </div>
							</div>
						</div>
					</div>
				</div>
				<form action="{{ route('newsoho.uploadVideo') }}" method="POST" enctype="multipart/form-data">
				    @csrf
				    <div class="form-group">
				        <div class="needsclick dropzone" id="document-dropzone">
				        </div>
				    </div>
				    <div class="text-right">
				        <input class="btn btn-primary btn-lg" type="submit" value="Upload">
				    </div>
				</form>
			</div>
			<hr>
		</div>
		<div class="newSoho-custom-container container">
			<p class="choose_brand_title">Elysium network</p>
			<div class="row">
				@if (isset($networkFileNames) && count($networkFileNames) > 0)
					@foreach($networkFileNames as $filename)
					<div class="col-lg-2 col-md-3 col-sm-4 col-6 img-preview-thumbnail">
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/network')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						<i class="fa fa-trash-o remove-sohovideo" data-brand="network" data-filename="{{ $filename }}"></i>
					</div>
					@endforeach
				@endif
			</div>
			<p class="choose_brand_title">Elysium capital</p>
			<div class="row">
				@if (isset($capitalFileNames) && count($capitalFileNames) > 0)
					@foreach($capitalFileNames as $filename)
					<div class="col-lg-2 col-md-3 col-sm-4 col-6 img-preview-thumbnail">
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/capital')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						<i class="fa fa-trash-o remove-sohovideo" data-brand="capital" data-filename="{{ $filename }}"></i>
					</div>
					@endforeach
				@endif
			</div>
			<p class="choose_brand_title">Elysium insider</p>
			<div class="row">
				@if (isset($insiderFileNames) && count($insiderFileNames) > 0)
					@foreach($insiderFileNames as $filename)
					<div class="col-lg-2 col-md-3 col-sm-4 col-6 img-preview-thumbnail">
						<div class="video-item-wrapper">
							<video controls>
							  <source src="{{asset('uploads/videos/insider')}}/{{ $filename }}#t=1" type="video/mp4">
							  <source src="movie.ogg" type="video/ogg">
							  Your browser does not support the video tag.
							</video>
						</div>
						<i class="fa fa-trash-o remove-sohovideo" data-brand="insider" data-filename="{{ $filename }}"></i>
					</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>


	<script>
		$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
	  var uploadedDocumentMap = {}
	  Dropzone.options.documentDropzone = {
	    url: '{{ route('newsoho.uploadVideo') }}',
	    maxFilesize: 200, // MB
	    addRemoveLinks: true,
	    headers: {
	      'X-CSRF-TOKEN': "{{ csrf_token() }}"
	    },
	    success: function (file, response) {
	      $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
	      uploadedDocumentMap[file.name] = response.name
	    },
	    removedfile: function (file) {
	      file.previewElement.remove()
	      var name = ''
	      if (typeof file.file_name !== 'undefined') {
	        name = file.file_name
	      } else {
	        name = uploadedDocumentMap[file.name]
	      }
	      $('form').find('input[name="document[]"][value="' + name + '"]').remove()
	    },
	    init: function () {
	      @if(isset($project) && $project->document)
	        var files =
	          {!! json_encode($project->document) !!}
	        for (var i in files) {
	          var file = files[i]
	          this.options.addedfile.call(this, file)
	          file.previewElement.classList.add('dz-complete')
	          $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
	        }
	      @endif
	    }
	  }
	  $(document).ready(function(){
      var brand = $("input[type='radio'].brand-group:checked").val();
      let send_data = {};
      send_data['brand'] = brand;
      $.ajax({
        type: 'POST',
        url: '{{ route('newsoho.storeBrand') }}',
        data: send_data,
        success: function(data) {
          console.log(data.success);
        },
        error: function(data){
          console.log(data);
        }
      })
    });

    $('.brand-group').change(function(){
      if( $(this).is(":checked") ) {
        var val = $(this).val();
        let send_data = {};
	      send_data['brand'] = val;
	      console.log(send_data)
	      $.ajax({
	        type: 'POST',
	        url: '{{ route('newsoho.storeBrand') }}',
	        data: send_data,
	        success: function(data) {
	          console.log(data.success);
	        },
	        error: function(data){
	          console.log(data);
	        }
	      })
      }
    });

	  $('.remove-sohovideo').click(function() {
	  	var filename = $(this).attr('data-filename');
	  	var brand = $(this).attr('data-brand');
	  	window.location.href = '{{ route('newsoho.removeFile') }}?type=video&brand='+brand+'&filename='+filename;
	  })
	</script>

@endsection