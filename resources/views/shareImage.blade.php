<html>
<head>
<title>{!! getConfig('company_information','company_name') !!} @if(isset($title)) -  {{ $title }}@endif</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<!-- You can use Open Graph tags to customize link previews.
Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
<meta property="og:url"           content="https://office.elysiumnetwork.io/shareImage" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="Elysium Network" />
<meta property="og:description"   content="Elysium site" />
<meta property="og:image"         content="{{ asset('uploads/images') }}/{{ $fileUrl }}?format=1000w" />
</head>
<body>
<a class="facebookLink" href="https://www.facebook.com/sharer/sharer.php?u=office.elysiumnetwork.io/shareImage&display=popup"> share this</a>
<script src="{{ asset('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		window.location.href = 'https://www.facebook.com/sharer/sharer.php?u=office.elysiumnetwork.io/shareImage&display=popup';
	})
</script>

</body>
</html>