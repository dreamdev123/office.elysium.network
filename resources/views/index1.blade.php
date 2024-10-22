<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<title>EquitiBO2</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="{{asset('assets/css/bootstrap_4.1.3.min.css')}}" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('assets/css/bootstrap_4.1.3.min.js')}}"></script>
	<style type="text/css">
		@font-face {
			font-family: "AmpBook";
			src:url("{{asset('assets/fonts/amplitude-book.ttf')}}");
		}
		@font-face {
			font-family: "AmpCondLight";
			src:url("{{asset('assets/fonts/amplitudecond-light.ttf')}}");
		}
		@font-face {
			font-family: "AmpLight";
			src:url("{{asset('assets/fonts/amplitude-light.ttf')}}");
		}
		@font-face {
			font-family: "AmpRegular";
			src:url("{{asset('assets/fonts/amplitude-regular.ttf')}}");
		}
		h2 {
			font-size: 29px;
		}
		.heading-logo {
			width: 100%;
			max-width: 800px;
		}
		.heading-logo .logo-img {
			margin-left: -117px;
			max-width: 310px;

		}
		.first-title {
			font-family: "AmpRegular";
		}
		.second-title {
			font-family: "AmpRegular";
		}
		.intro-block {
			margin-top: 53px;
			max-width: 800px;
		}
		.first-section {
			margin-top: 62px;
		}

		.logo-img3 {
			max-width: 247px;
		}
		.logo-img4 {
			max-width: 304px;
		}
		.logo-img5 {
			max-width: 232px;
		}
		.portolio-wrapper {
			max-width: 1200px;
		}
		.content-style {
			font-family: "AmpLight";
			font-size: 18px;
			text-align: justify;
			text-justify: inter-word;
		}
		.third-section {
			padding-right: 120px;
			padding-left: 120px;
		}
		
		.participate_btn {
			font-family: "AmpRegular";
			background-color: rgb(185, 183, 185);
			font-size: 22px;
			color: #FFF;
			padding-top: 10px;
			padding-bottom: 10px;
			border: none;
		}
		.footer {
			background-color: rgb(56, 57, 60);
		}
		.footer-content-style1 {
			font-family: "AmpCondLight";
			font-size: 22px;
			line-height: 1.2;
			color:  rgb(234, 234, 235);
			text-align: justify;
			text-justify: inter-word;
		}
		.footer-content-style {
			font-family: "AmpCondLight";
			font-size: 22px;
			color:  rgb(175, 175, 177);
			text-align: justify;
			text-justify: inter-word;
			line-height: 1.2;
		}
		.learn-more {
			font-family: "AmpCondLight";
			font-size: 22px;
			color: rgb(0, 175, 171);
		}
		.footer-content {
			font-family: "AmpBook";
			font-size: 18px;
			color: #FFF;
		}

		@media (min-width: 781px) {
		  .heading-logo {
				margin-top: 90px;
		  }
		}

		@media (max-width: 780px) {
		  .heading-logo {
			margin-top: 50px;
			padding-left: 30px;
			padding-right: 30px;
		  }
		  .heading-logo .logo-img {
		  	margin-left: 0px;
		  	width: 100%;
		  }
		  .participate_div {
		  	margin-top: 36px;
		  }
		  .intro-block {
		  	padding-left: 30px;
		  	padding-right: 30px;
		  }
		  .portolio-wrapper {
		  	padding-left: 30px;
		  	padding-right: 30px;
		  }
		}
		@media (min-width: 993px) and (max-width: 1160px) {
			.third-section {
				padding-left: 30px;
				padding-right: 30px;
			}
		}
		@media (min-width: 993px) {
		  .col-height {
		    height: 625px;
		  }
		  .content-fix {
		  	position: absolute;
		  	top: 112px;
		  }
		  .participate_div {
		  	position: absolute;
		  	width: 100%;
		  	bottom: 0;
		  	text-align: center;
		  }
		}
		@media (max-width: 992px) {
			.third-section {
				padding-left: 0px;
				padding-right: 0px;
			}
			.portolio-wrapper {
				max-width: 800px;
			}
		}
		@media (min-width: 1100px) {
		  .col-height {
		    height: 625px;
		  }
		  .content-fix {
		  	position: absolute;
		  	top: 112px;
		  }
		  .participate_div {
		  	position: absolute;
		  	width: 100%;
		  	bottom: 0;
		  	text-align: center;
		  }
		}

		@media (min-width: 1200px) {
		  .col-height {
		    height: 625px;
		  }
		  .content-fix {
		  	position: absolute;
		  	top: 112px;
		  }
		  .participate_div {
		  	position: absolute;
		  	width: 100%;
		  	bottom: 0;
		  	text-align: center;
		  }
		}

		@media (min-width: 1400px) {
		  .col-height {
		    height: 625px;
		  }
		  .content-fix {
		  	position: absolute;
		  	top: 112px;
		  }
		  .participate_div {
		  	position: absolute;
		  	width: 100%;
		  	bottom: 0;
		  	text-align: center;
		  }

		@media (min-width: 1550px) {
		  .col-height {
		    height: 625px;
		  }
		  .content-fix {
		  	position: absolute;
		  	top: 112px;
		  }
		  .participate_div {
		  	position: absolute;
		  	width: 100%;
		  	bottom: 0;
		  	text-align: center;
		  }
		}
		@media (min-width: 1860px) {
			.col-height {
		    height: 625px;
		  }
		}
	</style>
</head>
<body>
	<HEADER>
		<div class="container heading-logo">
			<img class="logo-img" src="{{asset('assets/img/equiti-elysium-logo.png')}}">
		</div>
	</HEADER>
	<CONTENT>
		<div class="container intro-block">
			<h2 class="first-title text-center">DISCRETIONARY MANAGED ACCOUNT PORTFOLIOS</h2>
			<div class="first-section">
				<img class="logo-img1" src="{{asset('assets/img/capital-logo-eq.png')}}">
				<div class="row" style="margin-top: 28px;">
					<div class="col">
						<p class="content-style">Elysium Capital Limited is an Asset Manager trading limited discretionary spot FX managed accounts. These trading accounts are held with Equiti Group Limited Jordan in their capacity as brokers. Clients authorize Elysium Capital Limited to trade on their behalf by granting us a Limited Power of Attorney allowing us to act on your behalf. </p>
					</div>
				</div>
			</div>
			<div class="second-section" style="margin-top: 58px;">
				<img class="logo-img2" src="{{asset('assets/img/equiti-01.png')}}">
				<div class="row" style="margin-top: 20px;">
					<div class="col">
						<p class="content-style">Equiti Group Limited Jordan is a limited liability incorporated in Jordan (with Registration No. 50248), and which is regulated by Jordan Securities Commission (JSC), offering its clients with access to individual, corporate and institutional brokerage services. </p>

						<p class="content-style" style="margin-top: 66px; margin-bottom: 0px;">Equiti utilises custodian services from world renowned banks such as J.P. Morgan Chase & Co. In addition to Equiti’s standard client protection features, eligible clients of Equiti benefit from their “Client Fund Insurance” that grants individual coverage of up to $1,000,000* per client at no direct cost to the clients at all.</p>
<!-- 						<p class="content-style">*>$25,000 - <$1,000,000.</p> -->
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid mt-5 portolio-wrapper">
			<h2 class="second-title text-center">ELYSIUM CAPITAL PORTFOLIOS</h2>
			<div class="third-section mb-5">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 p-0 col-height mt-5">
							<div class="w-100 text-center pr-2 pl-2">
								<img class="logo-img3 img-fluid" src="{{asset('assets/img/Screenshot_8.png')}}">
							</div>
							<p class="content-style mt-5 pr-3 pl-3 content-fix">This Quantitative intraday trading portfolio consists of trading strategies based on quantitative analysis on G10 currency pairs, which rely on mathematical computations and number crunching to identify trading opportunities. Price and volume are two of the more common data inputs used in quantitative analysis as the main inputs to mathematical models. Manual strategies such as price action strategies are implemented If an opportunity arises.</p>
							<div class="participate_div pr-3 pl-3">
								<button class="w-100 participate_btn">PARTICIPATE NOW</button>
							</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 p-0 col-height mt-5">
							<div class="w-100 text-center pr-2 pl-2">
								<img class="logo-img4 img-fluid text-center" src="{{asset('assets/img/Screenshot_9.png')}}">
							</div>
							<p class="content-style mt-5 pr-3 pl-3 content-fix">This world-class-performing portfolio is the genesis strategy and birthright of Elysium Capital with a continued objective and strategy which, for a decade, has achieved high performance with a solid risk-to-reward ratio. The objective of the Advisor’s Trading Program is to trade G10 and 45 Crosses currency pairs in a Trend following and High Frequency Trading (HFT) manner in order to achieve an above average absolute return compared to classic asset classes like stock and bond investments.</p>
							<div class="participate_div pr-3 pl-3">
								<button class="w-100 participate_btn">PARTICIPATE NOW</button>
							</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 p-0 col-height mt-5">
							<div class="w-100 text-center pr-2 pl-2">
								<img class="logo-img5 img-fluid text-center" src="{{asset('assets/img/Screenshot_10.png')}}">
							</div>
							<p class="content-style mt-5 pr-3 pl-3 content-fix">This portfolio trades currencies and precious metal commodities like gold, silver etc. strategies based quantitative analysis which rely on mathematical computations and number crunching to identify trading opportunities. Price and volume are two of the more common data inputs used in quantitative analysis as the main inputs to mathematical models. Manual strategies such as price action strategies can be implemented. Most positions are intraday, thus squared-off before the market closes.</p>
							<div class="participate_div pr-3 pl-3">
								<button class="w-100 participate_btn">PARTICIPATE NOW</button>
							</div>
					</div>
				</div>
			</div>
<!-- 			<p class="content-style text-center" style="margin-top: 100px; margin-bottom: 50px;">SUPPORT: <i class="fa fa-phone mr-3 ml-3" aria-hidden="true"> +962 6 550 8305 </i>   <i class="fa fa-phone mr-3" aria-hidden="true"> +44 203 519 2657 </i>  <i class="fa fa-envelope" aria-hidden="true"> support@equiti.com </i></p> -->
		</div>
	</CONTENT>
<!-- 	<FOOTER class="mt-5 footer pt-5 pb-5">
		<div class="container-fluid">
			<div class="third-section">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<div class="pr-3 pl-3">
							<div class="logo-content d-flex">
								<div>
									<img class="logo-img6 mr-auto ml-auto" src="{{asset('assets/img/Screenshot_11.png')}}">
								</div>
								<span class="footer-content-style1 ml-3">Trust is what you need when trading. We know how important it is to ensure the security and integrity of all your trading activity and your trust is of utmost value to us. We diligently work to meet the stringent standards set by the JSC in order to put our client's best interests at the centre of our business. <a href="#"><span class="learn-more">Click to learn more.</span></a></span>
							</div>
							<p class="footer-content-style mt-4">Margined Forex and CFD trading are leveraged products and can result in losses that exceed deposits. The value of your contract can fall as well as rise, which could result in receiving back less than you originally deposited. Please ensure you understand the risks and be sure to manage your risk exposure effectively. Equiti does not provide any investment advice.</p>
							<p class="footer-content-style mt-4">Services displayed on this website are provided by Equiti Group Limited Jordan & Equiti Capital UK Limited</p>
							<p class="footer-content-style mt-4">Equiti Group Limited Jordan is a registered trading name in Jordan (Registration Number. 50248), which is authorized and regulated by the Jordan Securities Commission with its company registered address at Second Floor, Jouba Complex, Suleiman Al Nabulsi St 32, Al-Abdali Boulevard, Amman.</p>
							<p class="footer-content-style mt-4">Equiti Capital UK Limited (Company No. 07216039) is authorised and regulated by the Financial Conduct Authority (firm reference no. 528328), with its company registered address at 69 Wilson Street, London, EC2A 2BB, UK</p>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<div class="pr-3 pl-3">
							<p class="footer-content-style">Card transactions are processed by Equiti Capital UK Limited.</p>
							<p class="footer-content-style mt-4">EGM Futures DMCC is registered in the United Arab Emirates as a broker & clearing member of Dubai Gold and Commodities Exchange (DGCX), licensed by Dubai Multi Commodities Centre (DMCC) under License Number 31573 and Regulated by the Securities & Commodities Authority (SCA) under License Number 607136.</p>
							<p class="footer-content-style mt-4">The information on this site is not directed at residents of the United States, Belgium, Canada, Singapore and is not intended for use by any person in any jurisdiction where such use would be contrary to local law or regulation. Telephone calls and online chat conversations may be recorded and monitored. Tax treatment depends on the individual circumstances for each client. Tax law can change or may differ in each jurisdiction.</p>
							<p class="footer-content-style mt-4">Any analysis, opinion, commentary or research-based material on our website is for information and educational purposes only and is not, in any circumstances, intended to be an offer, recommendation or solicitation to buy or sell. You should always seek independent advice as to your suitability to speculate in any related markets and your ability to assume the associated risks if you are at all unsure.</p>
							
							<img class="logo-img7 mr-auto ml-auto w-100" src="{{asset('assets/img/capital-footer-logo-eq.png')}}">
								
							<div class="text-center mt-3">
								<a href="#"><span class="footer-content mr-4">REGULATIONS</span></a> <a href="#"><span class="footer-content mr-4">LEGAL DOCUMENTATION</span></a> <a href="#"><span class="footer-content mr-4">TERMS OF USE</span></a> <a href="#"><span class="footer-content">RISK WARNING</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</FOOTER> -->
</body>
</html>