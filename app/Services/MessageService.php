<?php

namespace App\Services;

class MessageService
{
    public function sendCodeSms($contact_no, $book, $amount)
    {
        $to      = trim($contact_no);

        $message = 'Dear '. $book->user->name .',  You have successfully Paid '. $amount.' BDT for rental package . Your booking ID: SR_B-'.$book->id. ' , For any queries, please call 01880199801';


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => 'http://sms.sslwireless.com/pushapi/dynamic/server.php?user=shuttleltd&pass=90@1B88t&sid=shuttleltd&sms=' . urlencode("$message") . '&msisdn=' . $to . '&csmsid=WSDFCV',
            CURLOPT_USERAGENT      => 'Sample cURL Request',
        ]);
        $resp = curl_exec($curl);

        curl_close($curl);

        $res = simplexml_load_string($resp);

        return $res->LOGIN == 'SUCCESSFULL' ? true : false;
    }
}
