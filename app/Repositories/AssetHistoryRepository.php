<?php

namespace App\Repositories;

use App\Models\AssetHistory;

class AssetHistoryRepository
{
    protected $model;

    public function __construct(AssetHistory $assetHistory)
    {
        $this->model = $assetHistory;
    }

    public function getAssetHistory($assetId)
    {
        return $this->model->where('asset_id', $assetId)->get();
    }

}