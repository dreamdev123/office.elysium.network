<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="min-width: 100%;margin: 0;padding: 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;width: 100% !important;">
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
<table class="email_table" width="100%" border="0" cellspacing="0" cellpadding="0"
       style="">
    <tbody>
    <tr>
        <td class="email_body email_start tc">
            <div class="email_container">
                <div style="width: 100%; color: white">
                    <table class="column" width="100%" border="0" cellspacing="0" cellpadding="0" style="box-sizing: border-box;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;min-width: 100%;">

                           <div><img src="{{ asset('photos/EOSwhite.png') }}" style="width: 250px; float: left;"><div><br>
                           <div><h4 style="float: left;margin-left: auto; font-size: 18px; font-family: sans-serif;">RECEIPT</h4><div>
                        <tbody>
                        <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 22px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Invoice Number: {{$user->customer_id}}
                            </td>
                        </tr>
                         <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 2px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Attention: {{$user->metaData->firstname}} {{$user->metaData->lastname}}
                            </td>
                        </tr>
                        <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 1px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Client Registration No: {{$user->customer_id}}
                            </td>
                        </tr>
                        <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 1px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Address: {{$user->metaData->address}} 
                            </td>
                        </tr>
                        <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 1px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Postal Code:{{$user->metaData->postcode}} 
                            </td>
                        </tr>
                        <tr>
                            <td class="column_cell px pt_xs pb_0 logo_c tl sc"
                                style="box-sizing: border-box;vertical-align: top;width: 100%;min-width: 100%;padding-top: 1px;padding-bottom: 0;font-family: Helvetica, Arial, sans-serif;font-size: 14px;line-height: 150%;mso-line-height-rule: exactly;text-align: left;padding-left: 16px;padding-right: 16px;">
                                Payment Date: {{date('y-m-d')}} <br>
                                Payment Time:{{date('H:M')}}  <br>
                                IP Address: {{$_SERVER['REMOTE_ADDR']}}<br>
                                Country:  {{$user->metaData->country->name}}<br>
                                Email Address:  {{$user->email}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <table class="define" style="font-family: sans-serif;">
                        <thead style="color: white; background-color: black; font-weight: bold;font-size: 16px;padding: 10px;">
                            <tr>
                                <td style="padding: 15px;">Product Description</td>
                                <td style="padding: 15px;">Quantity</td>
                                <td style="padding: 15px;">Unit Price</td>
                                <td style="padding: 15px;">Cost</td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px; font-weight: bold">
                            @foreach($order->products as $product)
                            <tr>
                                <td style="padding-bottom: 8px;padding-top: 8px;border-bottom: dotted 2px grey;">{{$product->item->description}} </td>
                                <td style="padding-bottom: 8px;padding-top: 8px;border-bottom: dotted 2px grey;">{{$product->quantity}}</td>
                                <td style="padding-bottom: 8px;padding-top: 8px;border-bottom: dotted 2px grey;">€ {{$product->item->price}}</td>
                                <td style="padding-bottom: 8px;padding-top: 8px;border-bottom: dotted 2px grey;">€ {{$product->item->price * $product->quantity}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <table  class="define" style="font-family: sans-serif; font-size: 15px; font-weight: bold;">
                        <tr>
                            <td>Elysium Network Administration Team </td>
                        </tr>
                        <tr>
                            <td>admin@elysiumnetwork.io</td>
                        </tr>
                        <tr>
                            <td>Click HERE to Cancel your Monthly Subscription.  </td>
                        </tr>
                    </table>
                    <table style="display: flex; padding-top: 20px;padding-bottom: 20px;font-family: sans-serif;font-size: 12px;">
                        <tbody style="margin: auto;">
                            <tr>
                                <td style="text-align: center;">No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong. </td>
                                <td style="text-align: center;">Phone: +44 7723 503770 </td>
                            </tr>
                            <tr>
                                <td style="float: left;">Company Registration Number: 14895905  </td>
                                <td style="">Website: elysiumnetwork.io</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--[if (mso)|(IE)]></td></tr></tbody></table><![endif]-->
        </td>
    </tr>
    </tbody>
</table>
<!-- jumbotron_button -->

</body>
</html>




