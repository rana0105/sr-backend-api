<?php

namespace App\Repositories;

use App\Models\AirtableSyncTime;
use App\Models\Booking;
use App\Models\FavouriteRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Repository\Baserepository\BaseRepository;

class FavouriteRouteRepository extends BaseRepository
{
    private FavouriteRoute $favouriteRoute;

    public function __construct(FavouriteRoute $favouriteRoute)
    {
        $this->favouriteRoute = $favouriteRoute;
    }

    public function model(): FavouriteRoute
    {
       return $this->favouriteRoute;
    }

    public function usersFavRoutes(){
        $userCode = request()->user()->user_code;
        return $this->favouriteRoute->query()
            ->where('user_code',$userCode)
            ->get();
    }

}
