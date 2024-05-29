<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <title>Welcome Onboard Shuttle</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
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


    </style>
</head>
<body style="margin:0;background-color:#f9f9f9;">
<center class="wrapper" style="width:100%;table-layout:fixed;background-color:#f9f9f9; ">
    <table class="main" width="100%" style="background-color:#fff; width:100%;max-width:600px;border-spacing:0;font-family: 'Montserrat', sans-serif;color:#4a4a4a; border-radius: 30px; padding:20px 20px 0 20px">

        <!-- TITLE, TEXT -->
        <tr>
            <td style="padding: 5px 20px;">
                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding:0; ">
                            <p class="desktop-view" style="margin: 10px 0 0px 35px;font-size: 12px; font-weight: 600; color: #333333; text-align: right;font-family: 'Montserrat', sans-serif; ">{{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
                            <p style="margin: 10px 0 0px 35px;font-size: 12px; font-weight: 600; color: #333333; text-align: right;font-family: 'Montserrat', sans-serif; ">Booking ID: SRB-{{$details['id']}}</p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- LOGO SECTION -->
        <tr class="image">
            <td style="padding: 5px 0px; ">
                <table width="100%" style="border-spacing:0; ">
                    <tr>
                        <td style="padding:0;">
                        </td>
                        <td style="max-width:600px;">
                            <a href="https://www.shuttlebd.com"><img src="https://i.postimg.cc/GtjF68jj/Shuttle-Logo-002-01-1-1.png" alt="logo" title="" style="border:0;max-width:540px;  "></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- TITLE, TEXT -->
        <tr>
            <td style="padding:0;">
                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding:0; ">
                            <p class="desktop-view" style="font-size: 24px; font-weight: 500; color: #000;line-height: 32px; font-family: 'Montserrat', sans-serif;">Thank you for your rental request</p>
                            <p style="font-size: 16px; font-weight: 400; color: #333333;line-height: 32px;font-family: 'Montserrat', sans-serif;">Dear {{$details['user']['name']}},</p>

                            <p style="font-size: 16px; font-weight: 400; color: #333333;line-height: 32px;font-family: 'Montserrat', sans-serif;">Thank you for choosing Shuttle Rental for your trip. Our team will contact you shortly. If you have any query, you can always reach out to our team at +8801321139859. </p>


                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <!-- Two COLUMN SECTION -->
        <tr style="background-color: #F6F6F6;">
            <td style="padding:20px;border-radius: 10px;">

                <table width="100%" style="border-spacing:0;">
                    <tr>
                        <td class="three-columns" style="padding:0;font-size:0; border-bottom: 0.6px solid rgba(0, 0, 0, 0.24);">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Pickup Details</p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Location: {{$details['bookingLocation'][0]['address']}}</p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Date: {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('d/m/Y') ?? ''}}</p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Time: {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('g:i A') ?? ''}}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:260px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 600; color: #000; text-align: left;padding-left: 5px;">Drop-off Details</p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Location: {{$details['bookingLocation'][1]['address']}}</p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Date:
                                                        @if($details['way_type'] == 2)
                                                            {{\Carbon\Carbon::parse($details['return_date_time'])->format('d/m/Y') ?? '' }}
                                                        @endif
                                                    </p>
                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Time:
                                                        @if($details['way_type'] == 2)
                                                            {{\Carbon\Carbon::parse($details['return_date_time'])->format('g:i A') ?? '' }}
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
                </table>

                <table width="100%" style="border-spacing:0; padding-top: 10px;">
                    <tr>
                        <td class="three-columns" style="padding:0;font-size:0; ">
                            <table class="column1" style="border-spacing:0;width:100%;max-width:170px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                                        <table class="content" style="border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">

                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Car Type: {{$details['selectedCarType']['car_type_name']}}</p>


                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:170px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">

                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Max Seats: {{$details['selectedCarType']['sit_capacity']}}</p>


                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table class="column2" style="border-spacing:0;width:100%;max-width:170px;display:inline-block;vertical-align:top;">
                                <tr>
                                    <td class="padding" style="padding:0;">
                                        <table class="content" style="border-spacing:0;font-size:15px;">
                                            <tr>
                                                <td style="padding:0;">

                                                    <p style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Est. KM: {{ number_format(round($details['total_distance'] / 2)) }}</p>


                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    <tr>
                        <td style="padding:0;">
                            <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                                <tr>
                                    <td style="padding:0; ">
                                        <p class="desktop-view" style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Excludes: Parking Charge, Toll Charge & Extra KM Fare</p>



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
                                        <p class="desktop-view" style="margin: 0 0 10px;font-size: 16px; font-weight: 400; color: #333333; text-align: left;padding-left: 5px;">Includes: Fuel Cost, Driver Allowance</p>



                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tr>
                </table>


            </td>
        </tr>






        <tr>
            <td style="padding:0;">
                <table width="100%" style="border-spacing:0;max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding:0; ">


                            <p style="font-size: 16px; font-weight: 400; color: #333333;font-family: 'Montserrat', sans-serif;line-height: 24px;text-align: center;padding-top: 30px;">Tropical Molla (level 9 & 10),15/1-15/4, Bir Uttam Rafiqul Islam Ave,
                                Pragati Sarani,Middle Badda,Dhaka-1212, 09638-888868, 01321139859
                                info@shuttlebd.com, www.shuttlebd.com</p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="image">
            <td style=" ">
                <table width="100%" style="border-spacing:0; ">
                    <tr>
                        <td style="padding:0;">
                        </td>
                        <td style="max-width:600px;">
                            <a href="https://www.shuttlebd.com"><img src="https://i.postimg.cc/RF96S706/building-removebg-preview.png" alt="logo" title="" style="border:0;max-width:600px;  "></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


</center>

</body>
</html>

