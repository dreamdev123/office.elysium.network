@extends('User.Layout.master')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/newSoho.css') }}">

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
                <input type="radio" class="brand-group" name="elysiumType" value="network.png" data-title="Elysium Network" checked />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Network</span>
	            </label>
	            <label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="capital.png" data-title="Elysium Capital" />
                <span class="checkbox-circle"></span>
                <span class="checkbox-name">Elysium Capital</span>
	            </label>
	            <label class="radio-container">
                <input type="radio" class="brand-group" name="elysiumType" value="insider.png" data-title="Elysium Insider" />
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
						<p>Dear <span class="received_person">Name</span></p>
						<p>High performance is the conscious and intentional state of more aliveness and engagement, greater productivity and effectiveness, wider confidence and motivation, deeper satisfaction and fulfilment. It’s achieved through the ongoing awareness, practise and insight discovery of what fuels and drives the individual or team to add value and achieve success.</p>
						<p>It’s the art and science of serving something greater than yourself while simultaneously meeting your own needs.</p>
						<p>Mastery is the continual pursuit and superior development of the knowledge and skills necessary to live, lead and operate at your highest and best self.</p>
						<p>It’s the process of constant and never-ending improvement, the mindset of champion entrepreneurs and great leaders, and the pathway to your full potential.</p>
						<p>Have a great day,</p>
						<p class="my_name">Your Name</p>
						<p>Click <a href="https://www.elysiumnetwork.io/{{ loggedUser()->customer_id }}">here</a> to go to the website</p>
					</div>
					<div class="image-slider-buttons">
						<div class="button-group">
							<button class="left-arrow">Left</button>
							<span>Choose your Image</span>
							<button class="right-arrow">Right</button>
						</div>
					</div>
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
						<input type="email" class="soho-form-input input-email-address" name="" placeholder="Name">
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

		$('.send_email').click(function() {
			var received_person_name = $('.input-received-name').val();
			var my_name = $('.input-my-name').val();
			var send_email = $('.input-email-address').val();
			let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

      if (!re.test(send_email.toLowerCase()) || !send_email) {
      	toastr['error']("Please Insert Your Prospects Email-Address Correctly!");
      	return;
      } else if (!received_person_name) {
				toastr['error']("Please Insert Your Prospects Name Correctly!");
      	return;
			} else if (!my_name) {
				toastr['error']("Please Insert Your Name Correctly!");
      	return;
			}
			$(this).html('Sending...');
			$(this).attr('disabled', true);
			var send_data = {};
      send_data['bodyMessage'] = $('.soho-message-preview').html();
      send_data['send_email'] = send_email;
      $.ajax({
        type: 'POST',
        url: '{{ route('newsoho.sendEmail') }}',
        data: send_data,
        success: function(data) {
          toastr['success']("The email has been sent successfully.");
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