        <div class="row" style="margin-top: 40px;">
            <div class="col-sm-6">
                <img src="{{asset('images/ElysiumLogo.png')}}" class="logo" style="float: right; width: 50%;" />
            </div>
            <div class="col-sm-6"></div>
        </div>
        <div class="row">
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <h2><b>RECEIPT</b></h2>
            </div>
        </div>
        <div class="row" style="margin-top: 30px;">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Invoice Number: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$invoice_number}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Attention: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->metaData->firstname}} {{$user->metaData->lastname}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Client Registration No: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->customer_id}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Address: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->metaData->street_name}}, {{$user->metaData->house_no}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Postal Code: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->metaData->country->name}}, {{$user->metaData->postcode}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Payment Date: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$payment_date}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Payment Time: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$payment_time}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">IP Address: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">
                        @if(isset($ip))
                            {{$ip}}
                        @else
                            {{$_SERVER['REMOTE_ADDR']}}
                        @endif
                    </span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Country: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->metaData->country->name}}</span>
                </div>
                <div class="row">
                    <span style="float: left; font-size: 24px; font-family: sans-serif; width: 300px;">Email Address: </span>
                    <span style="font-size: 24px; font-family: sans-serif;">{{$user->email}}</span>
                </div>
            </div>
        </div>
        <div class="row text-center" style="margin-top: 70px;">
            <div class="col-sm-3">
                <span class="old_package" style="font-size: 24px; cursor: pointer;" onclick="packageClick('prev')"><i class="fa fa-chevron-left"></i></span>
            </div>
            <div class="col-sm-6">
                <table class="define" style="font-family: sans-serif; width: 100%;">
                    <thead style="color: white; background-color: black; font-weight: bold; font-size: 16px; padding: 10px;">
                        <tr>
                            <td style="padding: 15px;">Product Description</td>
                            <td style="padding: 15px;">Quantity</td>
                            <td style="padding: 15px;">Unit Price</td>
                            <td style="padding: 15px;">Cost</td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px; font-weight: bold" id="table-body">
                        <tr>
                            <td style="padding-bottom: 8px; padding-top: 8px; border-bottom: dotted 2px grey; ">
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
                            <td style="padding-top: 20px; border-bottom: solid 4px grey;"></td>
                            <td style="padding-top: 20px; border-bottom: solid 4px grey;"></td>
                            <td style="padding-top: 20px; border-bottom: solid 4px grey;"></td>
                            <td style="padding-top: 20px; border-bottom: solid 4px grey;"></td>
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
            <div class="col-sm-3">
                <span class="current_package" style="font-size: 24px; cursor: pointer;" onclick="packageClick('next')"><i class="fa fa-chevron-right"></i></span>
            </div>
        </div>
        <div class="row" style="margin-top: 60px;">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="row">
                    <span style="font-size: 24px; font-family: sans-serif;">Elysium Network Administration Team </span>
                </div>
                <div class="row">
                    <span style="font-size: 24px; font-family: sans-serif;">admin@elysiumnetwork.io</span>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 50px; background-color: black; padding: 10px;">
            <div class="row">
                <div class="col-sm-7 col-sm-offset-1" style="padding-left: 20px;">
                    <span style="font-size: 18px; font-family: sans-serif; color: white;">Elysium Capital Limited No.5, 17/F, Bonham Trade Centre, 50 Bonham Strand, Sheung Wan, Hong Kong. </span>
                    <br>
                    <span style="font-size: 18px; font-family: sans-serif; color: white">Company Registration Number: 2865940</span>
                </div>
                <div class="col-sm-3 col-sm-offset-1" style="padding-left: 20px;">
                    <span style="font-size: 18px; font-family: sans-serif; color: white">Phone: +44 7723 503770</span>
                    <br>
                    <span style="font-size: 18px; font-family: sans-serif; color: white">Website: elysiumnetwork.io</span>
                </div>
            </div>
        </div>