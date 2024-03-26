<?php

namespace App\Http\Controllers;

use App\Models\AssetHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function showHistory($assetId)
    {
        $histories = AssetHistory::where('asset_id',$assetId)->get();

        return response()->json(['histories' => $histories]);
    }
}
