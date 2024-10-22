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
              <!-- <label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="capital" data-title="Elysium Capital" {{ $brand == 'capital' ? 'checked' : '' }} />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Capital</span>
              </label> -->
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
              <img src="{{ asset($filename['src']) }}">
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
            <img class="preview-img" src="{{ asset($fileNames[0]['src']) }}">
            <div id="create-message-preview">
              <div id="create-message-header-preview"></div>
              <div id="create-message-tagline-preview"></div>
            </div>
            <div class="customer-id-preview">{{ $user->customer_id }}</div>
          </div>
          @endif
        </div>
        <div class="edit-option-section">
          <p class="title">Create Your Header</p>
          <textarea class="image-caption-header-input"></textarea>
          
          <p class="title">Create Your Tagline</p>
          <textarea class="image-caption-tagline-input"></textarea>

          <p class="soho-fb-share-title">Insert your facebook link for publishing</p>
          <input type="text" class="soho-fb-share-link-input" name="" placeholder="https://www.facebook.com/yourname">

          <div class="publish-button-group">
            <button class="download-btn" {{count($fileNames) < 1 ? 'disabled' : ''}}>Download</button>
            <button class="publish-btn facebook-modal" {{count($fileNames) < 1 ? 'disabled' : ''}}>Publish</button>
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
    });

    $(document).on('click', '.slick-arrow', function() {
      let src = $('.slick-current').find('img').attr('src');
      $('.preview-img').attr('src', src);
    })

    $('.brand-group').change(function(){
      if( $(this).is(":checked") ) {
        var brand = $(this).val();
        window.location.href = '{{ scopeRoute('soho') }}?brand=' + brand;
      }
    });

    $('.image-caption-header-input').on('keyup', function(e) {
      let send_data = {};
      send_data['content'] = $(this).val();
      $.ajax({
        type: 'POST',
        url: '{{ scopeRoute('soho.changeString') }}',
        data: send_data,
        success: function(data) {
          let header_content = '';
          $.each(data.content, function(key, value) {
              header_content += '<div>' + value + '</div>';
          })
          $('#create-message-header-preview').html(header_content);
        },
        error: function(data){
          console.log(data);
        }
      })
    })

    $('.image-caption-tagline-input').on('keyup', function(e) {
      let send_data = {};
      send_data['content'] = $(this).val();
      $.ajax({
        type: 'POST',
        url: '{{ scopeRoute('soho.changeString') }}',
        data: send_data,
        success: function(data) {
          let tagline_content = '';
          $.each(data.content, function(key, value) {
              tagline_content += '<div>' + value + '</div>';
          })
          $('#create-message-tagline-preview').html(tagline_content);
        },
        error: function(data){
          console.log(data);
        }
      })
    })

    $('.download-btn').on('click', function() {
      let selectedImageName = '';
      let src = $('.slick-current').find('img').attr('src');
      let arraySrc = src.split('/');
      if (arraySrc.length > 1) {
        selectedImageName = arraySrc[arraySrc.length - 1];
      }
      var brand = $("input[type='radio'].brand-group:checked").val();
      var header_content = $('.image-caption-header-input').val();
      var tagline_content = $('.image-caption-tagline-input').val();
      let send_data = {};
      send_data['backgroundImage'] = selectedImageName;
      send_data['brand'] = brand;
      send_data['header_content'] = header_content;
      send_data['tagline_content'] = tagline_content;
      $(this).html('Processing...');
      $(this).attr('disabled', true);
      $.ajax({
        type: 'POST',
        url: '{{ scopeRoute('soho.makeSohoImage') }}',
        data: send_data,
        success: function(data) {
          $('.download-btn').html('Download');
          $('.download-btn').attr('disabled', false);
          window.location.href = '{{ scopeRoute('soho.downloadSohoFile') }}?type=image&location=images&brand='+brand+'&filename='+data.filename;
        },
        error: function(data){
          console.log(data);
        }
      })
    })

    // $('.publish-btn').on('click', function() {
    //   let selectedImageName = '';
    //   let src = $('.slick-current').find('img').attr('src');
    //   arraySrc = src.split('/');
    //   if (arraySrc.length > 1) {
    //     selectedImageName = arraySrc[arraySrc.length - 1];
    //   }
    //  var brand = $("input[type='radio'].brand-group:checked").val();
    //  var fontweight = $("input[type='radio'].fontweight-group:checked").val();
    //  var aligntype = $("input[type='radio'].aligntype-group:checked").val();
    //  var content = $('.image-caption-input').html();
    //   let send_data = {};
    //   send_data['backgroundImage'] = selectedImageName;
    //   send_data['brand'] = brand;
    //   send_data['fontweight'] = fontweight;
    //   send_data['aligntype'] = aligntype;
    //   send_data['content'] = content;
    //   $.ajax({
    //     type: 'POST',
    //     url: '{{ scopeRoute('soho.makeSohoImage') }}',
    //     data: send_data,
    //     success: function(data) {
    //       window.open('{{ scopeRoute('soho.shareImage') }}?brand='+brand+'&customerId='+'{{$user->customer_id}}'+'&filename='+data.filename, '_blank');
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    //   })
    // }) 

  </script>
@endsection