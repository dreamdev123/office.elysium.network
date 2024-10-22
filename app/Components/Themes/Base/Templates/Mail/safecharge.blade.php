<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <title></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<!-- header_accent_icons -->
<table class="email_table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
            <!--[if (mso)|(IE)]>
            <table width="632" border="0" cellspacing="0" cellpadding="0" align="center"
                   style="vertical-align:top;width:632px;Margin:0 auto;">
                <tbody>
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
            <div class="email_container">
                <table class="content_section" width="100%" border="0" cellspacing="0" cellpadding="0"
                       style="box-sizing: border-box;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;min-width: 100%;">
                    <tbody>
                    <tr>
                        <td class="content_cell header_c active_b brt pt pb"
                            style="box-sizing: border-box;vertical-align: top;width: 100%;font-size: 0;padding-left: 16px;padding-right: 16px;border-radius: 4px 4px 0 0;padding-top: 16px;padding-bottom: 16px;line-height: inherit;min-width: 0 !important;">
                            <!-- col-2-4 -->
                            <div class="email_row"
                                 style="box-sizing: border-box;font-size: 0;display: block;width: 100%;vertical-align: top;clear: both;line-height: inherit;min-width: 0 !important;max-width: 600px !important;padding:10px 20px;">
                                <div><img src="{{asset('images/LogoNetworkRedBlack.png')}}" style="max-width: 250px; margin-bottom: 30px;"><br/></div>
                                <div><h4 style="width: 100%; font-size: 24px;text-align: center; margin-left: -50px; font-family: sans-serif;">Elysium {{$package->name}} - {{strtoupper($package->slug)}} Registration Details</h4></div>
                                <div class="col_12"
                                     style="box-sizing: border-box;font-size: 0;display: inline-block;width: 100%;vertical-align: top;line-height: inherit;min-width: 0 !important; margin-top: -20px">
                                    <table class="column" width="100%" border="0" cellspacing="0" cellpadding="0"
                                           style="box-sizing: border-box;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;min-width: 100%;">
                                        <tbody>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                               Dear {{$user->metaData->firstname}} <br/> Thank you for registering and welcome to Elysium! <br/>
Please read through this entire e-mail as it contains important info.
                                            </td>
                                        </tr>
                                         <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                Your ELYSIUM {{strtoupper($package->slug)}} ID: {{$user->customer_id}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 1px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                
                                                YOUR PERSONAL WEBSITES <br/>
                                                As an {{strtoupper($package->slug)}} ID you have been assigned 2 personal websites:

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                ELYSIUM | NETWORK <br/>
                                                1: http://www.elysiumnetwork.io/{{$user->customer_id}} <br/>
                                                On this website you can promote the business opportunity.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                ELYSIUM | CAPITAL <br/>
                                                2: http://www.elysiumcapital.io/{{$user->customer_id}} <br/>
                                                On this website you can introduce clients to the Portfolio Platform. <br/>
                                                [You can now place your own participation on this link above.] <br/>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                UPGRADES <br/>
                                                As an IB you can upgrade at any time to Founder, (If you haven't done already) : Log in your back office and click upgrade.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                SUPPORT <br/>
                                                If you have any questions or need assistance. Please contact us
                                                through your back office via your personal Elysiumnetwork site and
                                                use the Support section so we know it's you and that enables us to
                                                provide the fastest service.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;color: red; font-weight: bold;">
                                                If you can't get access: Please e-mail to support@elysiumnetwork.io
                                                and mention your registered name and Elysium ID number. Please
                                                also read the FAQ's on both websites.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                ACCURACY <br>
                                                We advise you to explore your backoffice and settings before you get
                                                started, if you need any assistance, don't hesitate to e-mail us, we're
                                                here to help.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                COMPLIANCE <br>
                                                Please be aware that you can only use Elysium approved marketing
                                                collateral. The reason is that you are operating in a strictly regulated
                                                industry. Misleading advertisement is damaging to our brand and
                                                reputation so please be careful and comply with our Terms & Conditions.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                REFUND POLICY - CHARGEBACK POLICY<br>
                                                Please check our Terms of Supply on our websites. We have a Refund
                                                policy. At all times you can reach us on support@elysiumnetwork.io and
                                                we will act fairly conform our Terms of Use.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                If you execute a Chargeback (i.e. demand from your credit card
                                                provider your funds back) this is considered a severe violation and we
                                                will have to prosecute it to the fullest extent of the law. The debt
                                                will be outsourced to our 3rd party Incasso Bureau (Craydon) and you
                                                might get permanently blacklisted for online purchases.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                We wish you all the best in your new endeavour and are at your service!

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                With warm regards,
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                Your Elysium IB/Affiliate support team.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                ELYSIUM | CAPITAL LIMITED <br>
                                                Office Turning Torso: Lilla Varvsgatan 14, 21115, Malmö, Sweden.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                ELYSIUM | CAPITAL LIMITED  REG: 2865940 <br>
                                                No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, <br>
                                                Hong Kong.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                                This email is confidential and may contain privileged or copyright
                                                information. If you are not [Recipient Email Address] please delete
                                                this email and you are notified that disclosing, copying, distributing
                                                or taking any action in reliance on the contents of this information
                                                is strictly prohibited.  This email is not a binding agreement and
                                                does not conclude an agreement without the express confirmation by the
                                                sender’s superior or a director of the Company. The Company does not
                                                consent to its employees or contractors sending non-solicited emails
                                                which contravene the law.
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--[if (mso)|(IE)]></td>
                                <td width="400"
                                    style="width:400px;line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
                                <![endif]-->
                                <div class="col_4"
                                     style="box-sizing: border-box;font-size: 0;display: inline-block;width: 100%;vertical-align: top;max-width: 400px;line-height: inherit;min-width: 0 !important;">
                                    {{--<table class="column" width="100%" border="0" cellspacing="0" cellpadding="0" style="box-sizing: border-box;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;min-width: 100%;">--}}
                                    {{--<tbody>--}}
                                    {{--<tr>--}}
                                    {{--<td class="column_cell px pb_0 pt_xs hdr_menu sc" style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 23px;mso-line-height-rule: exactly;text-align: right;padding-left: 16px;padding-right: 16px;">--}}
                                    {{--<p class="fsocial mb_0" style="font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 200%;color: #a9b3ba;mso-line-height-rule: exactly;display: block;margin-top: 0;margin-bottom: 0;"><a href="#" style="text-decoration: none;line-height: inherit;"><img src="{{ asset('email/images/android-settings.png') }}" width="24" height="24" alt="" style="outline: none;border: 0;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;line-height: 200%;max-width: 24px;height: auto !important;"></a></p>--}}
                                    {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--</tbody>--}}
                                    {{--</table>--}}
                                </div>
                                <!--[if (mso)|(IE)]></td></tr></tbody></table><![endif]-->
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--[if (mso)|(IE)]></td></tr></tbody></table><![endif]-->
        </td>
    </tr>
    </tbody>
</table>
<!-- jumbotron_button -->

</body>
</html>
