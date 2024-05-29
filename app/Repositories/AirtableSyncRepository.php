<?php

namespace App\Repositories;

use App\Models\AirtableSyncTime;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Repository\Baserepository\BaseRepository;

class AirtableSyncRepository extends BaseRepository
{
    private AirtableSyncTime $airtableSyncTime;

    public function __construct(AirtableSyncTime $airtableSyncTime)
    {
        $this->airtableSyncTime = $airtableSyncTime;
    }

    public function model(): AirtableSyncTime
    {
       return $this->airtableSyncTime;
    }

    public function sync(){
        $lastSyncTime = $this->lastSyncTime();

        if ($lastSyncTime == AirtableSyncTime::SYNC_ALL){
            $bookingsToBeSynced = Booking::query()->with('user')->get();
        }else{
            $bookingsToBeSynced = Booking::query()->with('user')->where('updated_at','>',$lastSyncTime)->get();
        }

        $syncingStartTime = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($bookingsToBeSynced as $bookingToBeSynced){
            try {
                $this->createOrUpdateToAirtable($bookingToBeSynced);
            }catch (\Throwable $exception){

            }
        }
        $airtableSyncTime = new AirtableSyncTime();
        $airtableSyncTime->synced_at = $syncingStartTime;
        $airtableSyncTime->synced_by = request()->user()->user_code;
        $airtableSyncTime->save();
        return $airtableSyncTime;
    }

    public function lastSyncTime(): string
    {
        $airtableLastSync = AirtableSyncTime::query()->orderBy('id', 'DESC')->first();
        if ($airtableLastSync){
            return Carbon::parse($airtableLastSync->synced_at)->format('Y-m-d H:i:s') ;
        }
        return AirtableSyncTime::SYNC_ALL;
    }

    private function createOrUpdateToAirtable($bookingToBeSynced)
    {
        $table = 'tblt7lWP8ewm2FARo';
        $url = 'https://api.airtable.com/v0/appWLtLDS0KKb0kbn/' . $table;
        $apiKey = 'Bearer keyd0cruzBqE3oatO';
        $phoneNumber = $bookingToBeSynced->user->phone;
        $bookingId = 'SRB-'.$bookingToBeSynced->id;
        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type' => 'application/json',
        ])->get($url, [
            'filterByFormula' => "Booking_ID = '{$bookingId}'",
        ]);
        if ($response->successful()) {
            $records = $response->json()['records'];
//            dd($records);
            if (count($records) == 0) {
//                 $response = Http::withHeaders([
//                     'Authorization' => $apiKey,
//                     'Content-Type' => 'application/json',
//                 ])->post("{$url}", [
//                     'fields' => [
//                         'fld3XmYQjTbD3LbZh' => $phoneNumber,
//                         'fldEye1EHSAaCj2RY' => $bookingId,
//                         'fldhaQ4yhY3bJ7InG' => $bookingToBeSynced->user->name,
//                         'fldZbVHM9zkkLlzLa' => '',
//                         'fldZLMjM7fJHjRWMG' => '',
//                         'fldagvW5YuFD59jML' => 'Todo',
//                         'fldMS0E1DSeJOcLtK' => $bookingToBeSynced->price,
//                         'fldY58o0P5YemXmvR' => $bookingToBeSynced->user->email
//                     ],
//                 ]);
//                 if ($response->ok()) {
//                     return response()->json([
//                         'message' => 'Record create or updated successfully'
//                     ]);
//                 } else {
//                     return response()->json([
//                         'message' => 'Failed to search for record',
//                         'errors' => $response->json(),
//                     ], $response->status());
//                 }
                Http::withHeaders([
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->post("{$url}", [
                    'fields' => [
                        'fld3XmYQjTbD3LbZh' => $phoneNumber,
                        'fldEye1EHSAaCj2RY' => $bookingId,
                        'fldhaQ4yhY3bJ7InG' => $bookingToBeSynced->user->name,
                        'fldagvW5YuFD59jML' => $bookingToBeSynced->status,
                        'fldMS0E1DSeJOcLtK' => $bookingToBeSynced->price,
                        'fldY58o0P5YemXmvR' => $bookingToBeSynced->user->email
                    ],
                ]);
            } else {
                $recordId = $records[0]['id'];
                Http::withHeaders([
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->patch("{$url}/{$recordId}", [
                    'fields' => [
                        'fld3XmYQjTbD3LbZh' => $phoneNumber,
                        'fldEye1EHSAaCj2RY' => $bookingId,
                        'fldhaQ4yhY3bJ7InG' => $bookingToBeSynced->user->name,
                        'fldagvW5YuFD59jML' => $bookingToBeSynced->status,
                        'fldMS0E1DSeJOcLtK' => $bookingToBeSynced->price,
                        'fldY58o0P5YemXmvR' => $bookingToBeSynced->user->email
                    ],
                ]);
            }
        }
    }
}
