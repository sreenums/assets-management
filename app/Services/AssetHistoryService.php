<?php

namespace App\Services;

use App\Repositories\AssetHistoryRepository;

class AssetHistoryService
{
    protected $assetHistoryRepository;

    public function __construct(AssetHistoryRepository $assetHistoryRepository)
    {
        $this->assetHistoryRepository = $assetHistoryRepository;
    }

    /**
     * For asset history show
     * 
     * @param $asset - Asset object
     */
    public function getAssetHistory($assetId)
    {
        return $this->assetHistoryRepository->getAssetHistory($assetId);
    }

}