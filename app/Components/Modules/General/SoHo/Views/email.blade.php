@extends('User.Layout.master')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css')}}">

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
                <input type="radio" class="brand-group" name="elysiumType" value="insider" data-title="Elysium Insider" {{ $brand == 'insider' ? 'checked' : '' }} />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Insider</span>
	            </label>
	          </div>
					</div>
				</div>
			</div>
		</div>

		<div class="newSoho-custom-container">
			<div class="soho-image-edit-section">
				<div class="preview-section">
					<p class="title">Content Preview</p>
					<div class="soho-message-preview">
						<p>Hello <span class="received_person">Name</span>! <span class="my_name">Your Name</span> here.</p>

						<p>I know you’re busy; therefore, I promise not to waste your time. I also promise that the plan I want to run past you can be extremely lucrative. And I know for a fact that IF you decline, you will lose out.</p>

						<p>Click here to learn more and make up your own mind. It will just take you a few minutes to assess. Very worthwhile!</p>

						<p>Once you've checked my website, just shoot me an e-mail and we’ll head on to the next step. No obligations, you can back off anytime and I won't bother you further.</p>

						<p>With warmest regards and care about your financial future <span class="my_name">Your Name</span></p>

						<p>P.S. Check this out right away, not to miss out! You’re welcome to attend one of our calls, anonymously, to explore further. You take no risk and there is so much to gain.</p>

						<p>You received this email because you've either subscribed or been referred manually by a friend/acquaintance via "Tell-A-Friend".</p>

						<p>If you no longer want to receive these emails, please click on the link below:</p>

						<p>Click here to unsubscribe - thanks! :-) It's instant and permanent.</p>

						<p>ELYSIUM | CAPITAL LIMITED REG: 2865940</p>
						<p>No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong.</p>

						<p>Elysium Network is a trademark of</p>
						<p>Copyright 2021 Elysium Capital Limited</p>
					</div>
					{{--<div class="image-slider-buttons">
						<div class="button-group">
							<button class="left-arrow">Left</button>
							<span>Choose your Image</span>
							<button class="right-arrow">Right</button>
						</div>
					</div>--}}
				</div>
				<div class="soho-contact-form">
					<div class="soho-input-group">
						<p class="title">Insert Your Name</p>
						<input type="text" class="soho-form-input input-my-name" name="" placeholder="Name">
					</div>
					<div class="soho-input-group">
						<p class="title">Insert Your Prospects Name</p>
						<input type="text" class="soho-form-input input-received-name" name="" placeholder="Name">
					</div>
					<div class="soho-input-group">
						<p class="title">Insert Your Prospects Email-Address</p>
						<input type="email" class="soho-form-input input-email-address" name="" placeholder="Email-Address">
					</div>
					<div class="soho-form-submit-button">
						<button class="send_email">Start Campaign</button>
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
		$('.input-received-name').on('keyup', function() {
			var received_person_name = $(this).val();
			$('.received_person').html(received_person_name);
		})
		$('.input-my-name').on('keyup', function() {
			var my_name = $(this).val();
			$('.my_name').html(my_name);
		})

    $('.brand-group').change(function(){
      if( $(this).is(":checked") ) {
        var brand = $(this).val();
        window.location.href = '{{ scopeRoute('soho.email') }}?brand=' + brand;
      }
    });

		$('.send_email').click(function() {
			var brand = $("input[type='radio'].brand-group:checked").val();
			var received_person_name = $('.input-received-name').val();
			var my_name = $('.input-my-name').val();
			var send_email = $('.input-email-address').val();
			let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

      if (!re.test(send_email.toLowerCase()) || !send_email) {
      	toastr['error']("Please Inser Your Prospects Email-Address Correctly!");
      	return;
      } else if (!received_person_name) {
				toastr['error']("Please Inser Your Prospects Name Correctly!");
      	return;
			} else if (!my_name) {
				toastr['error']("Please Inser Your Name Correctly!");
      	return;
			}
			$(this).html('Sending...');
			$(this).attr('disabled', true);
			var send_data = {};
      send_data['send_email'] = send_email;
      send_data['from_name'] = my_name;
      send_data['to_name'] = received_person_name;
      send_data['brand'] = brand;
      $.ajax({
        type: 'POST',
        url: '{{ scopeRoute('soho.sendEmail') }}',
        data: send_data,
        success: function(data) {
          toastr['success']("You have sended successfully!");
					$('.send_email').html('Start Campaign');
					$('.send_email').attr('disabled', false);
        },
        error: function(data){
          toastr['error']("Error!");
        }
      })
		})
	</script>
@endsection