<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>{{ config('app.name') }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #000;
                color: white;
            }
        </style>
</head>
<body>
<div style="max-width: 800px;">
  	<div id="invoice">
        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-6">
                <img src="{{asset('images/ElysiumLogo.png')}}" class="logo" style="padding-left: 20px; width: 80%; max-width: 300px;" />
            </div>
            <div class="col-sm-6"></div>
        </div>
        <div class="row">
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <h2 style="float: right;"><b>RECEIPT</b></h2>
            </div>
        </div><br><br>
        <div class="row" style="margin-top: 50px; width: 100%">
            <div class="text-center" style="text-align: center;">
                <table class="define" style="font-family: sans-serif; width: 80%; margin-left: auto; margin-right: auto;">
                    <tbody style="font-size: 16px;">
                        <tr>
                            <td style="width: 200px;">Invoice Number: </td>
                            <td>{{$invoice_number}}</td>
                        </tr>
                        <tr>
                            <td>Attention: </td>
                            <td>{{$user->metaData->firstname}} {{$user->metaData->lastname}}</td>
                        </tr>
                        <tr>
                            <td>Client Registration No: </td>
                            <td>{{$user->customer_id}}</td>
                        </tr>
                        <tr>
                            <td>Address: </td>
                            <td>{{$user->metaData->street_name}}, {{$user->metaData->house_no}}</td>
                        </tr>
                        <tr>
                            <td>Postal Code: </td>
                            <td>{{$user->metaData->country->name}}, {{$user->metaData->postcode}}</td>
                        </tr>
                        <tr>
                            <td>Payment Date: </td>
                            <td>{{$payment_date}}</td>
                        </tr>
                        <tr>
                            <td>Payment Time: </td>
                            <td>{{$payment_time}}</td>
                        </tr>
                        <tr>
                            <td>IP Address: </td>
                            <td>
                                @if(isset($ip))
                                    {{$ip}}
                                @else
                                    {{$_SERVER['REMOTE_ADDR']}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Country: </td>
                            <td>{{$user->metaData->country->name}}</td>
                        </tr>
                        <tr>
                            <td>Email Address: </td>
                            <td>{{$user->email}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row text-center" style="margin-top: 70px; width: 100%">
            <div class="text-center" style="text-align: center;">
                <table class="define" style="font-family: sans-serif; width: 80%; margin-left: auto; margin-right: auto;">
                    <thead style="color: white; background-color: black; font-weight: bold; font-size: 16px; padding: 10px;">
                        <tr>
                            <td style="padding: 15px; width: 280px">Product Description</td>
                            <td style="padding: 15px;">Quantity</td>
                            <td style="padding: 15px;">Unit Price</td>
                            <td style="padding: 15px;">Cost</td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px; font-weight: bold">
                        <tr>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey;">
                                @if($context == 'subscription')
                                    Subscription: <br/>
                                    XOOM&trade; - SoHo&trade; - EOS&trade;
                                @else
                                    {{$package_description}}
                                @endif
                            </td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey;">1</td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey;">€ {{$package_price}}</td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey;">€ {{$package_price}}</td>
                        </tr>
                        @if($admin_fee > 0)
                        <tr>
                            <td style="padding-bottom: 8px; padding-top: 8px;">Admin Fee</td>
                            <td style="padding-bottom: 8px; padding-top: 8px;"></td>
                            <td style="padding-bottom: 8px; padding-top: 8px;"></td>
                            <td style="padding-bottom: 8px; padding-top: 8px;">€ 49.95</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding-top: 20px; border-bottom: solid 4px grey;" colspan="4"></td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 8px; padding-top: 8px;">Amount Paid</td>
                            <td style="padding-bottom: 8px; padding-top: 8px;"></td>
                            <td style="padding-bottom: 8px; padding-top: 8px;">Total</td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey;">€ {{$package_total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="margin-top: 70px; width: 100%">
            <div class="text-center" style="text-align: center;">
                <table class="define" style="font-family: sans-serif; width: 80%; margin-left: auto; margin-right: auto;">
                    <tbody style="font-size: 16px;">
                        <tr>
                            <td>Elysium Network Administration Team </td>
                        </tr>
                        <tr>
                            <td>admin@elysiumnetwork.io</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<footer>
    <table style="font-family: sans-serif; width: 100%; margin-left: auto; margin-right: auto;">
        <thead style="">
            <tr>
                <td style="padding-top: 15px; padding-left: 45px; color: white; font-weight: bold; font-size: 12px;">
                    Elysium Capital Limited No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong.
                    <br>
                    Company Registration Number: 2865940
                </td>
                <td style="padding-top: 15px; color: white; font-weight: bold; font-size: 12px;">
                    Phone: +44 7723 503770
                    <br>
                    Website: elysiumnetwork.io
                </td>
            </tr>
        </thead>
    </table>
</footer>
</body>
</html> 

<!-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>{{ config('app.name') }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 2cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #000;
                color: white;
            }
        </style>
</head>
<body>
<div style="max-width: 800px;">
    <div id="invoice">
        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-6">
                <img src="{{asset('images/ElysiumLogo.png')}}" class="logo" style="padding-left: 20px; width: 80%; max-width: 300px;" />
            </div>
            <div class="col-sm-6"></div>
        </div>
        
        <div class="row text-center" style="margin-top: 70px; width: 100%">
            <div class="text-center" style="text-align: center;">
                
                <table class="define" style="font-family: sans-serif; width: 95%; margin-left: auto; margin-right: auto;">
                    <thead style="color: white; background-color: black; font-weight: bold; font-size: 12px; padding: 10px;">
                        <tr>
                            <td style="padding: 15px; width: 150px">Full Name</td>
                            <td style="padding: 15px;">Username</td>
                            <td style="padding: 15px;">Email</td>
                            <td style="padding: 15px;">Country Name</td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 10px; font-weight: bold">
                        @if(isset($user_group))
                        @foreach($user_group as $user_data)
                        <tr>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dashed 1px grey;">{{$user_data['fullname']}} </td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dashed 1px grey;">{{$user_data['username']}}</td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dashed 1px grey;">{{$user_data['email']}}</td>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dashed 1px grey;">{{$user_data['country']}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<footer>
    <table style="font-family: sans-serif; width: 100%; margin-left: auto; margin-right: auto;">
        <thead style="">
            <tr>
                <td style="padding-top: 15px; padding-left: 45px; color: white; font-weight: bold; font-size: 12px;">
                    Elysium Capital Limited No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong.
                    <br>
                    Company Registration Number: 2865940
                </td>
                <td style="padding-top: 15px; color: white; font-weight: bold; font-size: 12px;">
                    Phone: +44 7723 503770
                    <br>
                    Website: elysiumnetwork.io
                </td>
            </tr>
        </thead>
    </table>
</footer>
</body>
</html> -->