<?php

namespace App\Repositories;

use App\Models\MailSettings;
use Illuminate\Support\Facades\Http;
use Repository\Baserepository\BaseRepository;

class MailSettingsRepository extends BaseRepository
{

    private MailSettings $mailSettings;

    public function __construct(MailSettings $mailSettings)
    {
        $this->mailSettings = $mailSettings;
    }

    function model(): MailSettings
    {
        return $this->mailSettings;
    }

    public function getMailTypes(){
        return  $this->mailSettings->getMailTypes();
    }

    public function mailSettingByType($type){

      return  MailSettings::query()
            ->where('mail_type', $type)
            ->first();
    }

    public function sendHtmlMail($requestType , array $emails){
        $url  				= env('NOTIFICATION_SERVICE_URL');
        $api  				= '/api/v1/send-email';
        try {
         return   Http::withHeaders([
                'Authorization' => 'Bearer '.env('NOTIFICATION_SERVICE_TOKEN')
            ])->post($url.$api, [
                'request_type' => $requestType,
                'emails' => $emails
            ])->json();
        } catch (\Throwable $exception) {

        }
    }
}
