
        
    <style type="text/css">
		.templateContainer p{
			margin:10px 0;
			padding:0;
		}
	
		.templateContainer h1{
			color:#07b58f !important;
			font-family:Helvetica;
			font-size:40px;
			font-style:normal;
			font-weight:bold;
			line-height:100%;
			letter-spacing:-1px;
			text-align:center;
		}
	
		

		#templatePreheader{
			background-color:#FFFFFF;
			border-top:0;
			border-bottom:1px solid #D5D5D5;
		}
	
		.preheaderContainer .mcnTextContent,.preheaderContainer .mcnTextContent p{
			color:#606060;
			font-family:Helvetica;
			font-size:11px;
			line-height:125%;
			text-align:left;
		}

		.preheaderContainer .mcnTextContent a{
			color:#26ABE2;
			font-weight:normal;
			text-decoration:underline;
		}
	
		#templateHeader{
			background-color:#EEEEEE;
			border-top:0;
			border-bottom:0;
		}
	
		.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
			color:#606060;
			font-family:Helvetica;
			font-size:15px;
			line-height:150%;
			text-align:left;
		}
	
		.headerContainer .mcnTextContent a{
			color:#26ABE2;
			font-weight:normal;
			text-decoration:underline;
		}
	
		#templateBody{
			background-color:#EEEEEE;
			border-top:0;
			border-bottom:0;
		}
	
		#bodyBackground{
			background-color:#FFFFFF;
			border:1px solid #D5D5D5;
		}

		.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
			color:#606060;
			font-family:Helvetica;
			font-size:15px;
			line-height:150%;
			text-align:left;
		}
	
		.bodyContainer .mcnTextContent a{
			color:#07b58f;
			font-weight:normal;
			text-decoration:underline;
		}
	
		#templateFooter{
			background-color:#363636;
			border-top:1px solid #000000;
			border-bottom:0;
		}
	
		.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
			color:#CCCCCC;
			font-family:Helvetica;
			font-size:11px;
			line-height:125%;
			text-align:center;
		}
	
		.footerContainer .mcnTextContent a{
			color:#CCCCCC;
			font-weight:normal;
			text-decoration:underline;
		}
	
	</style></head>
       
        <table border="0" cellpadding="0" cellspacing="0" width="600" class="templateContainer">
                                                    <tr>
                                                        <td align="center" valign="top">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="bodyBackground">
                                                                <tr>
                                                                    <td valign="top" class="bodyContainer" style="padding-top:10px; padding-bottom:10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	<!--[if mso]>
				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
				<tr>
				<![endif]-->
			    
				<!--[if mso]>
				<td valign="top" width="598" style="width:598px;">
				<![endif]-->
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                        
                            <h1><span style="font-size:28px">7 Days to Account Expiration</span></h1>

                        </td>
                    </tr>
                </tbody></table>
				<!--[if mso]>
				</td>
				<![endif]-->
                
				<!--[if mso]>
				</tr>
				</table>
				<![endif]-->
            </td>
        </tr>
    </tbody>
</table><table class="mcnTextBlock" style="min-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td class="mcnTextBlockInner" style="padding-top:9px;" valign="top">
              	<!--[if mso]>
				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
				<tr>
				<![endif]-->
			    
				<!--[if mso]>
				<td valign="top" width="599" style="width:599px;">
				<![endif]-->
                <table style="max-width:100%; min-width:100%;" class="mcnTextContentContainer" width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
                    <tbody><tr>
                        
                        <td class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;" valign="top">
                        
                            <br>
														<span style="font-size:21px"><strong>ELYSIUM | ACCOUNT EXPIRATION ALERT</strong></span><br>
														<br>
														Greetings {{$user->username}}.<br>
														<br>
														Please be notified that your Elysium Account will expire in 7 days.<br>
														<br>
														We suggest that you login to your Back Office, and do the following to ensure<br>
														that you continue to retain access to your Elysium Account.<br>
														<br>
														1. Login to office.elysiumnetwork.io/user/login<br>
														2. Click My Profile, on the Left Menu<br>
														3. Click the Subscriptions Tab on the Right Screen<br>
														<br>
														On that screen, you will have the option to make payment by<br>
														Credit Card or by IBAN / SWIFT Transfer.<br>
														<br>
														Please do ensure that payment is received by us, before your account expires,<br>
														so that you can continue to maintain access to your Elysium Account.&nbsp;<br>
														<br>
														If you have any questions or remarks, don't hesitate to contact our<br>
														support team at: &nbsp;support@elysiumnetwork.io &nbsp;For the fastest response<br>
														log in your back office and contact us.<br>
														<br>
														Name: {{$user->username}}<br>
														Position: {{$user->package->slug}}<br>
														ID: {{$user->customer_id}}<br>
														Phone: {{$user->phone}}<br>
														E-mail: {{$user->email}}<br>
														<br>
														With warm regards,<br>
														<br>
														Your Elysium IB/Affiliate support team.<br>
														<br>
														ELYSIUM | CAPITAL LIMITED<br>
														Office Turning Torso: Lilla Varvsgatan 14, 21115, Malmö, Sweden<br>
														<br>
														No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<br>
														Company Registration Number: 14895905 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<br>
														Phone: &nbsp; &nbsp; &nbsp; +44 7723 503770&nbsp;<br>
														Website: &nbsp; &nbsp; elysiumnetwork.io<br>
														<br>
														This email is confidential and may contain privileged or copyright<br>
														information. If you are not [Recipient Email Address] please delete<br>
														this email and you are notified that disclosing, copying, distributing<br>
														or taking any action in reliance on the contents of this information<br>
														is strictly prohibited. &nbsp;This email is not a binding agreement and<br>
														does not conclude an agreement without the express confirmation by the<br>
														sender’s superior or a director of the Company. The Company does not<br>
														consent to its employees or contractors sending non-solicited emails<br>
														which contravene the law.<br>
														<br>
														Copyright © 2020 Elysium Capital Limited, All rights reserved.<br>
														You are receiving this email because you opted in via our website.<br>
														<br>
														Our mailing address is:<br>
														Elysium Capital Limited<br>
														Lilla Varvsgatan 14<br>
														Malmo 21115<br>
														Sweden
                        </td>
                    </tr>
                </tbody></table>
				<!--[if mso]>
				</td>
				<![endif]-->
                
				<!--[if mso]>
				</tr>
				</table>
				<![endif]-->
            </td>
        </tr>
    </tbody>
</table></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    
                                                </table>
