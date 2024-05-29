<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <title></title>
    <style type="text/css">

    </style>
</head>
<body style="margin:0;background-color:#f5f5f5;">
Dear Shuttler, <br/><br/>
Greetings from our Shuttle Rental team !!<br/><br/>
Thank you for requesting rental service. Your desired trip request is confirmed and here is your booking id : SRB-{{$details['id']}}. <br/><br/>
We have received your request and our team will reach you shortly. For further queries, you can also contact us at 09638-888868, 01880199801 <br/><br/>
Your trip details are provided below.<br><br>
<table style="border: 1px solid black; border-collapse: collapse; width: 100%; text-align: center">
    <tr style="border-bottom: 1px solid black; border-collapse: collapse; background: #523b98">
        <td style="border-right: 1px solid black; color: white">
            Particulars
        </td>
        <td style="color: white">
            Details
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Name
        </td>
        <td>
            {{$details['user']['name']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Phone
        </td>
        <td>
            {{$details['user']['phone']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Email
        </td>
        <td>
            {{$details['user']['email']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Trip Type
        </td>
        <td>
            {{$details['way_type'] == 1 ?  'One Way' : 'Round Trip'}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Car Type
        </td>
        <td>
            {{$details['selectedCarType']['car_type_name']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Pickup Location
        </td>
        <td>
            {{$details['bookingLocation'][0]['address']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Drop off Location
        </td>
        <td>
            {{$details['bookingLocation'][1]['address']}}
        </td>
    </tr>
    <tr style="border-bottom: 1px solid black; border-collapse: collapse">
        <td style="border-right: 1px solid black">
            Pickup Time
        </td>
        <td>
            {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('d/M/Y') ?? ''}} {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('g:i A') ?? ''}}
        </td>
    </tr>
    @if($details['way_type'] == 2 )
        <tr style="border-bottom: 1px solid black; border-collapse: collapse">
            <td style="border-right: 1px solid black">
                Return Time
            </td>
            <td>
                {{\Carbon\Carbon::parse($details['return_date_time'])->format('d/M/Y') ?? '' }} {{\Carbon\Carbon::parse($details['return_date_time'])->format('g:i A') ?? '' }}
            </td>
        </tr>
    @endif
    <tr>
        <td style="border-right: 1px solid black">
           Est. Rental Fare
        </td>
        <td>
            BDT {{$details['price']}}*
        </td>
    </tr>

</table>
</body>
</html>
