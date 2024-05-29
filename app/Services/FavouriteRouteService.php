<?php

namespace App\Services;


use App\Repositories\FavouriteRouteRepository;

class FavouriteRouteService
{
    private FavouriteRouteRepository $favouriteRouteRepository;

    public function __construct(FavouriteRouteRepository $favouriteRouteRepository){
        $this->favouriteRouteRepository = $favouriteRouteRepository;
    }

    public function fetchAll(){
        return $this->favouriteRouteRepository->getAll();
    }

    public function usersFavRoutes(){
        return $this->favouriteRouteRepository->usersFavRoutes();
    }

    public function favRouteCreate($data){
        $data['user_code'] = request()->user()->user_code;
        return $this->favouriteRouteRepository->create($data);
    }

    public function favRouteUpdate($data){
        $data['user_code'] = request()->user()->user_code;
        return $this->favouriteRouteRepository->updateByID($data['id'], $data);
    }

}
