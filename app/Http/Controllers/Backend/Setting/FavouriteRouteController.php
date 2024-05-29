<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Services\FavouriteRouteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavouriteRouteController extends Controller
{
    private FavouriteRouteService $favouriteRouteService;

    public function __construct(FavouriteRouteService $favouriteRouteService){
        $this->favouriteRouteService = $favouriteRouteService;
    }

    public function index(){
       $favouriteRoutes = $this->favouriteRouteService->fetchAll();
       return response()->json(['message'=>'All favourite routes', 'data' => $favouriteRoutes]);
    }

    public function usersFavouriteRoutes(): JsonResponse
    {
        $favouriteRoutes = $this->favouriteRouteService->usersFavRoutes();
        return response()->json(['message'=>'Favourite routes', 'data' => $favouriteRoutes]);
    }

    public function favRouteCreate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'from_place_id' => 'required',
            'to_place_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $favRouteCreated = $this->favouriteRouteService->favRouteCreate($validator->getData());
        if ($favRouteCreated){
            return response()->json(['message'=>'Favourite route created']);
        }
        return response()->json(['message'=>'Favourite route creation failed']);
    }

    public function favRouteUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\FavouriteRoute,id',
            'from' => 'required',
            'to' => 'required',
            'from_place_id' => 'required',
            'to_place_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 406);
        }
        $favRouteUpdated = $this->favouriteRouteService->favRouteUpdate($validator->getData());
        if ($favRouteUpdated){
            return response()->json(['message'=>'Favourite route updated', 'data' => $favRouteUpdated]);
        }
        return response()->json(['message'=>'Favourite route update failed']);
    }



}
