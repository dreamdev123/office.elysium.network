<!DOCTYPE html>
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>

   <!--- basic page needs
   ================================================== -->
   <meta charset="utf-8">
	<title>Elysium Office</title>
	<meta name="description" content="">  
	<meta name="author" content="">

   <!-- mobile specific metas
   ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- CSS
   ================================================== -->
   <link rel="stylesheet" href="{{asset('css/comming/base.css')}}">  
   <link rel="stylesheet" href="{{asset('css/comming/main.css')}}">
   <link rel="stylesheet" href="{{asset('css/comming/vendor.css')}}">

   <!-- script
   ================================================== -->
	<script src="{{asset('js/comming/modernizr.js')}}"></script>

   
</head>

<body id="top">

	<!-- header 
   ================================================== -->
   <header>

   	<div class="row">
   		<div class="logo">
	         <a href="javascript:;">Khronos</a>
	      </div> 

   	</div> 

   </header> <!-- /header -->   

   <!-- home
   ================================================== -->
   <section id="home" class="home-static">

   	<div class="shadow-overlay"></div>

   	<div class="content-wrap-table">		   

        <div class="main-content-tablecell">

            <div class="row">
                <div class="col-twelve">

                    <div id="counter" style="display: flex;">
                        <div class="half" style="margin-left: auto;">
                            <span id="hour">23 <sup>hours</sup></span>
                        </div>
                        <div class="half" style="margin-right: auto;">
                            <span id="mins">50 <sup>mins</sup></span>
                            <span id="secs">33 <sup>secs</sup></span>
                        </div> 
                    </div>


                    <div class="bottom-text">
                        <h1 style="margin: auto;text-align: center;">Scheduled Server Maintenance</h1>
                        <p style="margin: auto;text-align: center;width: auto;">ELYSIUM Server Maintenance - 30 April 2020 <br/>We will be back online soon.</p>
                    </div>
                </div> <!-- /twelve --> 

            </div> <!-- /row -->  

        </div> <!-- /main-content --> 
		   
    </div> <!-- /content-wrap -->
   
</section> <!-- /home -->


   <!-- info
   ================================================== -->
 

   <div id="preloader"> 
    	<div id="loader"></div>
   </div> 

   <!-- Java Script
   ================================================== --> 
   <script src="{{asset('js/comming/jquery-2.1.3.min.js')}}"></script>
   <script src="{{asset('js/comming/plugins.js')}}"></script>
   <script src="{{asset('js/comming/main.js')}}"></script>
   <script type="text/javascript">
   		window.setInterval(function(){
   			var now = new Date();
   			var dest = new Date();
   			dest.setHours(11);
   			dest.setMinutes(0);
   			dest.setSeconds(0);
   			dest.setMonth(4);
   			dest.setDate(1);

   			console.log(now.getTime());
   			var miliseconds = dest.getTime() - now.getTime();

   			var seconds = Math.floor(miliseconds / 1000)  % 60;
   			var mins = Math.floor(miliseconds /  60 /1000) % 60;
   			var hour = Math.floor(miliseconds / 60 / 60 / 1000);

   			hour = hour < 10?'0' + hour:hour;
   			mins = mins < 10?'0' + mins:mins;
   			seconds = seconds < 10?'0' + seconds:seconds;
   			$('#hour').html(hour + ' <sup>hours</sup>');
   			$('#mins').html(mins + ' <sup>mins</sup>');
   			$('#secs').html(seconds + ' <sup>seconds</sup>');
   		},1000);
   </script>

</body>

</html>