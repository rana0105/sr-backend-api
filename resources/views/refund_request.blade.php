<!doctype html>
<html lang="en">
<body>
<table>
    <tr>
        Refund Request Time : {{\Carbon\Carbon::now()->format('d/M/Y') ?? ''}} {{\Carbon\Carbon::now()->format('g:i A') ?? ''}}
    </tr>
    <tr>Booking Id : SRB-{{$details['booking_details']['id']}}</tr>
    <tr>Name : {{$details['booking_details']['user']['name']}}</tr>
    <tr>Phone : {{$details['booking_details']['user']['phone']}}</tr>
    <tr>Email : {{$details['booking_details']['user']['email']}}</tr>
    <tr>Refund Amount : {{$details['refund_amount']}}</tr>
    <tr>Transaction Ids :
        @foreach ($details['trx_ids'] as $item)
            {{ $item }} @if (!$loop->last) , @endif
        @endforeach
    </tr>
</table>
</body>
</html>
