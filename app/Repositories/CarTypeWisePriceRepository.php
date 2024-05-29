<?php

namespace Repository;

use App\Models\CarTypeWisePrice;
use App\Models\FareSettings;
use App\Models\GeneralFareSettings;
use App\Repositories\FareSettingsRepository;
use App\Repositories\GoogleApiRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Repository\Baserepository\BaseRepository;
use Image;

class CarTypeWisePriceRepository extends BaseRepository
{
    private CarTypeWisePrice $carTypeWisePrice;
    private GoogleApiRepository $googleApiRepository;

    public function __construct(CarTypeWisePrice $carTypeWisePrice, GoogleApiRepository $googleApiRepository)
    {
        $this->carTypeWisePrice = $carTypeWisePrice;
        $this->googleApiRepository = $googleApiRepository;
    }

    public function model(): CarTypeWisePrice
    {
        return $this->carTypeWisePrice;
    }

    public function carTypeWisePriceWithImageUrl(): Collection
    {
       return $this->carTypeWisePrice::query()->orderBy('id', 'desc')->get()->map(function ($collection){
            $imageUrl =  url('/images/'.$collection->image);
            return [
                'id' => $collection->id,
                'car_type_name' => $collection->car_type_name,
                'car_model'     => $collection->car_model,
                'sit_capacity'  => $collection->sit_capacity,
                'minimum_fare'  => $collection->minimum_fare,
                'per_km_fare'   => $collection->per_km_fare,
                'waiting_charge'=> $collection->waiting_charge,
                'night_stay_charge'   => $collection->night_stay_charge,
                'image'         => $imageUrl
            ];
        });
    }

    public function updateCarTypeWisePrice($id, $data){
        $carTypePrice                      = $this->carTypeWisePrice->findOrfail($id);
        $carTypePrice->car_type_name       = $data['car_type_name'];
        $carTypePrice->sit_capacity        = $data['sit_capacity'];
        $carTypePrice->minimum_fare        = $data['minimum_fare'];
        $carTypePrice->per_km_fare         = $data['per_km_fare'];
        $carTypePrice->waiting_charge      = $data['waiting_charge'];
        $carTypePrice->night_stay_charge   = $data['night_stay_charge'];
        $carTypePrice->updated_by    = request()->user()->user_code;
        if ($data['image']){
            $destinationPath = public_path('images/');
            if($carTypePrice->image != ''  && $carTypePrice->image != null){
                $file_old = $destinationPath.$carTypePrice->image;
                if( $file_old){
                    try {
                        unlink($file_old);
                    }catch(\Throwable $exception){

                    }
                }
            }
            $image = Image::make($data['image']);
            $imageName = time().'-'.$data['image']->getClientOriginalName();
            $image->save($destinationPath.$imageName);
            $carTypePrice->image = $imageName;
        }
        $carTypePrice->save();
        return $carTypePrice;
    }

    public function kmWisePrice($data){
        return $this->carTypeWisePrice::query()->get()->map(function ($collection) use($data){
            $imageUrl =  url('/images/'.$collection->image);
            return [
                'id' => $collection->id,
                'car_type_name' => $collection->car_type_name,
                'car_model'     => $collection->car_model,
                'sit_capacity'  => $collection->sit_capacity,
                'minimum_fare'  => $collection->minimum_fare,
                'per_km_fare'   => $collection->per_km_fare,
                'approx_fare'   => $collection->minimum_fare + $collection->per_km_fare * $data['km'],
                'image'         => $imageUrl
            ];
        });
    }

    public function durationInHr($durationInText){
        $hr = $min = 0;
        $timeArray = explode(" ", $durationInText);
        $hrsExists = array_search('hours', $timeArray);
        $hrExists = array_search('hour', $timeArray);
        $minExists = array_search('mins', $timeArray);
        if ($hrsExists){
            $hr = $timeArray[$hrsExists -1];
        }
        if ($hrExists){
            $hr = $timeArray[$hrExists -1];
        }
        if ($minExists){
            $min = $timeArray[$minExists -1];
        }
        return $hr +  (round($min / 60 , 2) ?? 0);
    }

    public function tripPrices($data){

        $pickupAddress  = $this->googleApiRepository->placeDetails($data['pickupPlaceId']);
        $dropOffAddress = $this->googleApiRepository->placeDetails($data['dropoffPlaceId']);

        $data['generalFareSetting'] = GeneralFareSettings::query()->first();
        $data['addressOneLat'] = $pickupAddress['lat'];
        $data['addressOneLng'] = $pickupAddress['lng'];
        $data['fromDistrict']  = $pickupAddress['district'];
        $data['addressTwoLat'] = $dropOffAddress['lat'];
        $data['addressTwoLng'] = $dropOffAddress['lng'];
        $data['toDistrict']    = $dropOffAddress['district'];
        list($data['distance'], $durationInText)    = $this->googleApiRepository->getDistanceAndDuration($data);
        $data['hrNeededToReachDest'] = $this->durationInHr($durationInText);
        $data['waitingTime'] = 0;
        if ($data['way_type'] == 2){
            $diff = Carbon::parse($data['pickup_date_time'])->diffInHours(Carbon::parse($data['return_date_time']));
            $data['waitingTime'] = round(max(0, $diff - $data['hrNeededToReachDest']), 2);
        }
        $N = $this->percentageIncrementUponKm($data['distance']); //percentage Increment Upon Km = N
        $L= $data['distance'] * 2 * $N;

        $multipliers = FareSettings::query()
            ->with(['carTypeWiseMultiplier'])
            ->where('from', $data['fromDistrict'])
            ->where('to', $data['toDistrict'])
            ->first()['carTypeWiseMultiplier'] ?? [];
        $prices =  $this->carTypeWisePrice::query()
            ->get()
            ->map(function ($collection) use ($N, $L, $multipliers, $data){
               $multiplierByCar = collect($multipliers)->where('car_type_wise_prices_id', $collection['id'])->first();
               $M  = $multiplierByCar['multiplier_value'] ?? $this->multiplierBasedOnL($L, $collection['car_type_name']) ;
               $oneWayFare = round(($collection['minimum_fare'] * $M) + ($L * $collection['per_km_fare']) + $data['generalFareSetting']['profit_value']);
               if ($data['way_type'] == 2){
                   if($data['waitingTime'] > $data['generalFareSetting']['waiting_charge_upto']){
                       $waitingCharge = round(ceil($data['waitingTime'] / 24) * $collection['night_stay_charge'], 2) ;
                   }else{
                       $waitingCharge = round($data['waitingTime'] * $collection['waiting_charge'],2);
                   }
                   $fare = ($oneWayFare * $data['generalFareSetting']['round_trip_multiplier']) + $waitingCharge;
               }else{
                   $fare = $oneWayFare;
               }
               $imageUrl =  url('/images/'.$collection->image);
               return [
                    'id'            => $collection->id,
                    'car_type_name' => $collection->car_type_name,
                    'car_model'     => $collection->car_model,
                    'sit_capacity'  => $collection->sit_capacity,
                    'approx_fare'   => ceil($fare / 10) * 10 ,
                    'waiting_time'  => $data['waitingTime'],
                    'travel_time'   => $data['hrNeededToReachDest'],
                    'waiting_charge'=> $waitingCharge ?? 0,
                    'image'         => $imageUrl
                ];
            })->values();
        return array($prices, $data['distance']);

    }

    public function percentageIncrementUponKm($distance){
        if ($distance>= 150){
            return 1.05;
        }
        elseif ($distance>= 50 && $distance <= 149){
            return 1.10;
        }else{
            return 1.20;
        }
    }

    public function multiplierBasedOnL($L, $carName) {
        if ($L >= 610){
            if ($carName == 'sedan' || $carName == 'Sedan'){
                return 3.00;
            }
            return 3.00;
        }elseif ($L >= 530 && $L <=609){
            if ($carName == 'sedan' || $carName == 'Sedan'){
                return 2.00;
            }
            return 2.5;
        }elseif ($L >= 60 && $L <= 529){
            if ($carName == 'sedan' || $carName == 'Sedan'){
                return 1.5;
            }
            return 1.35;
        }else{
            return 1.00;
        }
    }

}
