<!doctype html>
<html lang="en">
<body>
<table>
    <tr>Booking Id : SRB-{{$details['id']}}</tr>
    <tr>Name : {{$details['user']['name']}}</tr>
    <tr>Phone : {{$details['user']['phone']}}</tr>
    <tr>Email : {{$details['user']['email']}}</tr>
    <tr>Way Type : {{$details['way_type'] == 1 ?  'One Way' : 'Round Trip'}}</tr>
    <tr>Car Type : {{$details['selectedCarType']['car_type_name']}}</tr>
    <tr>
        Pickup Location: {{$details['bookingLocation'][0]['address']}}
    </tr>
    <tr>
        Drop off Location: {{$details['bookingLocation'][1]['address']}}
    </tr>
    <tr>
        Pickup Time : {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('d/M/Y') ?? ''}} {{\Carbon\Carbon::parse($details['pickup_date_time'])->format('g:i A') ?? ''}}
    </tr>
    @if($details['way_type'] == 2 )
    <tr>
        Return Time : {{\Carbon\Carbon::parse($details['return_date_time'])->format('d/M/Y') ?? '' }} {{\Carbon\Carbon::parse($details['return_date_time'])->format('g:i A') ?? '' }}
    </tr>
    @endif
    <tr>
        Booking Price : {{$details['price']}} tk
    </tr>

</table>
</body>
</html>
