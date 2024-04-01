<?php

namespace App\Http\Controllers;

use App\Models\AssetHistory;
use App\Services\AssetHistoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $assetHistoryService;

    public function __construct(AssetHistoryService $assetHistoryService)
    {
        $this->assetHistoryService = $assetHistoryService;
    }

    public function showHistory($assetId)
    {
        $histories = AssetHistory::where('asset_id',$assetId)->get();

        return response()->json(['histories' => $histories]);
    }


}
