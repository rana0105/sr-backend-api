<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class GoogleApiRepository
{
    public function placeDetails($location): array
    {
        $url = env('GOOGLE_MAP_API_URL').'place/details/json?placeid=';
        $key = env('GOOGLE_MAP_API_KEY');
        $placeData = Http::get($url.$location.'&key='.$key)->json();

        $districtName = str_replace(' District' ,'',
            collect($placeData['result']['address_components'])
            ->filter(function ($item) {
            return in_array('administrative_area_level_2', $item['types']);
        })->first()['long_name']) ?? '';

        $data['address']    = $placeData['result']['name'];
        $data['district']   = $districtName;
        $data['lat']        = $placeData['result']['geometry']['location']['lat'];
        $data['lng']        = $placeData['result']['geometry']['location']['lng'];
        return $data;
    }

    public function getDistance($data){
        $url = env('GOOGLE_MAP_API_URL').'distancematrix/json';
        $key = env('GOOGLE_MAP_API_KEY');
        $origins = $data['addressOneLat'].','.$data['addressOneLng'];
        $destinations = $data['addressTwoLat'].','.$data['addressTwoLng'];
        $distanceData =  Http::get($url, [
            'key' => $key,
            'origins' => $origins,
            'destinations' => $destinations
        ])->json();
        if ($distanceData['status'] = 'OK'){
            return trim($distanceData['rows'][0]['elements'][0]['distance']['text'], ' km');
        }
        return false;
    }

    public function getDistanceAndDuration($data){
        $url = env('GOOGLE_MAP_API_URL').'distancematrix/json';
        $key = env('GOOGLE_MAP_API_KEY');
        $origins = $data['addressOneLat'].','.$data['addressOneLng'];
        $destinations = $data['addressTwoLat'].','.$data['addressTwoLng'];
        $distanceData =  Http::get($url, [
            'key' => $key,
            'origins' => $origins,
            'destinations' => $destinations
        ])->json();
        if ($distanceData['status'] = 'OK'){
            $km = trim($distanceData['rows'][0]['elements'][0]['distance']['text'], ' km');
            $duration = $distanceData['rows'][0]['elements'][0]['duration']['text'];
            return array($km, $duration);
        }
        return false;
    }

    public function queryAutoComplete($data)
    {
        $key = env('GOOGLE_MAP_API_KEY');
        $url = env('GOOGLE_MAP_API_URL').'place/queryautocomplete/json?input='.$data['input'].
            '&region=BD&components=country:BD&types=geocode&key='.$key;
        $data =  Http::get($url)->json();
        return collect($data['predictions'])->map(function ($c){
            $secondString = $c['terms'][count($c['terms'])-1]['value'] ?? '';
            $placeId = $c['place_id'] ?? '';
          return [
              'name' => $c['structured_formatting']['main_text'] != $secondString
                  ? $c['structured_formatting']['main_text'].', '.$secondString : $c['structured_formatting']['main_text'],
              'place_id' => $placeId
          ];
        })->where('place_id','!=', '');
    }
}
