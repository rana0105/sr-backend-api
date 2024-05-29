<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <title></title>
    <style type="text/css">
        body {
            Margin: 0;
            padding: 0;
        }
        table {
            border-spacing: 0;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
        }
        @media only screen and (max-width: 480px) {
            .column2 {
                margin-top: 20px;
            }
            .column1{
                margin-right: 0px !important;
            }
            .design{
                padding-left: 0px !important;
                margin: 0 0 10px 0 !important;
                padding-top: 10px !important;
            }
            .design1{
                padding-left: 0px !important;
                margin: -20px 0 10px 0 !important;
                padding-top: 10px !important;
            }
            .desktop-view{
                padding-left: 25px !important;
            }
            .padding{
                padding: 0px !important;
            }
            .desktop-view1{
                padding-left: 0px !important;
            }
        }


    </style>
</head>
<body style="margin:0;background-color:#f5f5f5;">
<center class="wrapper" style="width:100%;table-layout:fixed;background-color:#f5f5f5;">
    <table class="main" width="100%" style="background-color:#fff; width:100%;max-width:600px;border-spacing:0;font-family: 'Roboto', sans-serif;color:#4a4a4a; border-radius: 30px;">


        <!-- LOGO SECTION -->
        <tr class="image">
            <td style="padding: 25px 0px; ">
                <table width="100%" style="border-spacing:0; ">
                    <tr>
                        <td style="padding:0;">
                        </td>
                        <td style="max-width:400px;">
                            <a href="https://www.shuttlebd.com"><img src="https://i.postimg.cc/d3Pzc710/logo.png" alt="logo" title="" style="border:0;max-width:400px; width:50px;height: 50px; padding:0px 30px;text-align: center;float: right; "></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                <table class="column" style="border-spacing:0;width:100%;max-width:200px;display:inline-block;vertical-align:top;">
                    <tr>
                        <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                            <table class="content" style="border-spacing:0;">
                                <tr>
                                    <td style="padding:0;">
                                        <a href="https://www.shuttlebd.com"><img src="https://i.postimg.cc/sxJynPcz/c03ff3c0-e941-410e-b251-37b3e98cds2215-removebg-preview.png" alt="logo" title="" style="border:0;float:right; padding:0px 30px;text-align: center;float: right; "></a>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="column" style="border-spacing:0;width:100%;max-width:320px;display:inline-block;vertical-align:top;">
                    <tr>
                        <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                            <table class="content" style="border-spacing:0;font-size:15px;">
                                <tr>
                                    <td style="padding:0;">
                                        <p style="margin: 0 0 0 10px;font-size: 35px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;padding-top: 50px;">Have a Great Ride</p>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>



        <tr>
            <td style="padding:0;">
                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding:0; ">
                            <p class="desktop-view1" style="margin: 10px 0 10px 35px;font-size: 16px; font-weight: 500; color: #000;line-height: 5px; padding-left: 10px;text-align: center;">Date: {{\Carbon\Carbon::now()->format('d/M/Y')}}</p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:0;">
                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding:0; ">
                            <p class="desktop-view1" style="margin: 10px 0 10px 35px;font-size: 16px; font-weight: 500; color: #000;line-height: 5px; padding-left: 10px;text-align: center;">Transaction ID: {{$details['transaction_id']}}</p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <!-- Two COLUMN SECTION -->
        <tr>
            <td style="padding:0;">
                <table width="100%" style="border-spacing:0;padding: 45px 0 0px 0px;">
                    <tr>
                        <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p style="margin: 0 0 10px;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Service Type: Shuttle Rental</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p class="design1" style="margin: 0 0 0 10px;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Trip Type: {{$details['booking']['way_type'] == 1 ? 'One Way' : 'Round Trip'}}
                                                    </p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <!--   <tr>
                 <td style="padding:0;">
                   <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                     <tr>
                       <td style="padding:0; ">
                         <p class="desktop-view" style="margin: 10px 0 10px 35px;font-size: 15px; font-weight: 500; color: #000;line-height: 35px; padding-left: 10px;">Pickup Details</p>

                       </td>
                     </tr>
                   </table>
                 </td>
               </tr> -->

                    <tr>
                        <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;background-color: #f5f5f5;margin-right: 10px;margin-top: 20px;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;">Pickup Location: {{$details['booking']['bookingLocation'][0]['address']}}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;background-color: #f5f5f5;margin-top: 20px;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p  style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;">Drop off location: {{$details['booking']['bookingLocation'][1]['address']}}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p class="design" style="margin: 10px 0 10px 0;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Pickup Date: {{\Carbon\Carbon::parse($details['booking']['pickup_date_time'])->format('d/M/Y') }}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p class="design1" style="margin: 10px 0 10px 0;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 15px;">Return Date:
                                                        @if($details['booking']['way_type'] == 2)
                                                        {{\Carbon\Carbon::parse($details['booking']['return_date_time'])->format('d/M/Y') }}
                                                        @endif
                                                    </p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;background-color: #f5f5f5;margin-right: 10px">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p  style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;">Pickup Time: {{\Carbon\Carbon::parse($details['booking']['pickup_date_time'])->format('g:i A') }}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;background-color: #f5f5f5;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p  style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;">Return Time:
                                                        @if($details['booking']['way_type'] == 2)
                                                        {{\Carbon\Carbon::parse($details['booking']['return_date_time'])->format('g:i A') }}</p>
                                                        @endif

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <!--
                                   <tr>
                              <td style="padding:0;">
                                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                                  <tr>
                                    <td style="padding:0; ">
                                      <p class="desktop-view" style="margin: 10px 0 10px 35px;font-size: 15px; font-weight: 500; color: #000;line-height: 35px; padding-left: 10px;">Package Type:</p>

                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr> -->
                    <tr>
                        <td style="padding:0;">
                            <table width="100%" style="border-spacing:0;padding: 10px 0 0px 0px;">
                                <tr>
                                    <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                                        <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                            <tr>
                                                <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                                    <table class="content" style="border-spacing:0;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <p style="margin: 0 0 10px;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Paying Now: {{$details['amount']}}</p>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                            <tr>
                                                <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                                    <table class="content" style="border-spacing:0;font-size:15px;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <p class="design1" style="margin: 0 0 0 10px;font-size: 15px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Vehicle Type: {{$details['booking']['selectedCarType']['car_type_name']}}</p>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                                        <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;background-color: #f5f5f5;margin-right: 10px">
                                            <tr>
                                                <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                                    <table class="content" style="border-spacing:0;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <p style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;">Total Price: {{$details['booking']['price']}} Tk</p>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                            <tr>
                                                <td class="padding" style="padding:0;padding:0 0px 0px 10px;">
                                                    <table class="content" style="border-spacing:0;font-size:15px;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <p style="margin: 0 0 10px;font-size: 15px; font-weight: 500; color: #000; text-align: left;padding-top: 5px;padding-left: 10px;"></p>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                                            <tr>
                                                <td style="padding:0; ">
                                                    <p class="desktop-view" style="margin: 0px 0 10px 35px;font-size: 15px; font-weight: 500; color: #000;line-height: 35px; padding-left: 10px;">Total Paid: {{$details['booking']['paid_amount']}} Tk</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @php
                                $due = $details['booking']['price'] -$details['booking']['paid_amount'];
                                $dueAfterThisPayment = $due - $details['amount']
                                @endphp
                                <tr>
                                    <td style="padding:0;">
                                        <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                                            <tr>
                                                <td style="padding:0; ">
                                                    <p class="desktop-view" style="margin: 0px 0 10px 35px;font-size: 15px; font-weight: 500; color: #000; padding-left: 10px;">Total Due: {{$due}} Tk</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                                            <tr>
                                                <td style="padding:0; ">
                                                    <p class="desktop-view" style="margin: 0px 0 10px 35px;font-size: 15px; font-weight: 500; color: #000;line-height: 35px; padding-left: 10px;">Remaining Due: {{$dueAfterThisPayment}} Tk</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>



                                <tr>
                                    <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                                        <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                            <tr>
                                                <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                                    <table class="content" style="border-spacing:0;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <a href="{{$details['payment_link']}}" style="text-decoration: none"><p style="margin: 10px 0 0 0px;font-size: 15px; font-weight: 600; color: #fff;background-color: #6c3a97;padding:10px 20px;border-radius: 10px; text-align: center;text-decoration: none;">Make Payment</p></a>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;padding-bottom: 50px">
                                            <tr>
                                                <td class="padding" style="">
                                                    <table class="content" style="border-spacing:0;font-size:15px;">
                                                        <tr>
                                                            <td style="padding:0;">
                                                                <p style="margin: 0 20px 0 10px;font-size: 15px; font-weight: 400; color: #000; float: right;text-align: right;"><b>Contact Us</b><br>Tropical Molla (level 9 & 10),15/1-15/4, Bir Uttam Rafiqul Islam Ave, Pragati Sarani,Middle Badda,Dhaka-1212<br>09638-888868,01880199801 <br>info@shuttlebd.com,www.shuttlebd.com</p>


                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                                <!-- Two COLUMN SECTION -->

                            </table>
</center>

</body>
</html>
