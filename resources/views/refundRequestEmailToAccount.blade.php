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
                  <p style="font-size: 16px; font-weight: 400; color: #333333;line-height: 25px;font-family: 'Montserrat', sans-serif;">Dear Concern,</p>
                  <p style="font-size: 16px; font-weight: 400; color: #333333;line-height: 25px;font-family: 'Montserrat', sans-serif;">The following customer requested a refund. As per our policy, he/she is eligible for refund. The customer details are as follows:</p>

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
                <td class="three-columns" style="padding:0;text-align:center;font-size:0;">
                  <table class="column" style="border-spacing:0;width:100%;max-width:540px;display:inline-block;vertical-align:top; ">
                    <tr>
                      <td class="padding" style="padding:0;padding:0 10px 0px 0px;">
                        <table class="content" style="border-spacing:0;">
                          <tr>
                            <td style="padding:0;">
                              <p style="margin: 0 0 10px;font-size: 16px; font-weight: 600; color: #000; text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;">Customer Details</p>
                              <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px;">Booking ID: SRB-{{$details['booking_details']['id']}} </p>
                               <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px">Transactions ID: @foreach ($details['trx_ids'] as $item)
                                       {{ $item }} @if (!$loop->last) , @endif
                                   @endforeach </p>
                                <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px">Amount: {{$details['refund_amount']}} tk</p>

                                <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px">Name:  {{$details['booking_details']['user']['name']}}</p>
                                <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px">Phone: {{$details['booking_details']['user']['phone']}}</p>
                                <p style="margin: 0;font-weight: 400; font-size: 16px; color: #333333;text-align: left;line-height: 24px;font-family: 'Montserrat', sans-serif;padding-bottom: 5px">Email: {{$details['booking_details']['user']['email']}}</p>
                            </td>
                          </tr>
                        </table>
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
                  <p class="desktop-view" style="font-size: 16px; font-weight: 400; color: #333333;line-height: 25px;font-family: 'Montserrat', sans-serif;">Kindly, proceed with the refund. We appreciate your support in processing the refund promptly and addressing this issue. </p>
                   <p class="desktop-view" style="font-size: 16px; font-weight: 400; color: #333333;line-height: 25px;font-family: 'Montserrat', sans-serif;">Best regards,<br>
Shuttle Rental
</p>

                  <p style="font-size: 16px; font-weight: 400; color: #000;line-height: 24px;text-align: center;font-family: 'Montserrat', sans-serif;">Tropical Molla (level 9 & 10),15/1-15/4, Bir Uttam Rafiqul Islam Ave,
                  Pragati Sarani,Middle Badda,Dhaka-121209638-888868,01880199801
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

