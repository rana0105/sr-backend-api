<?php

namespace App\Http\Controllers;

use App\Services\AirtableSyncService;
use Illuminate\Http\Request;

class AirtableSyncController extends Controller
{
    private AirtableSyncService $airtableSyncService;

    public function __construct(AirtableSyncService $airtableSyncService){
        $this->airtableSyncService = $airtableSyncService;
    }

    public function sync(){
       $data = $this->airtableSyncService->sync();
       if ($data){
           return response()->json(['data' => $data, 'message' => 'Syncing done'], 200);
       }
       
        return response()->json(['message' => 'Syncing unsuccessful'], 500);
    }
}
