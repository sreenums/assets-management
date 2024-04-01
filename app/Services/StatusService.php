<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use App\Repositories\StatusRepository;

class StatusService
{
    protected $assetRepository;
    protected $statusRepository;

    public function __construct(AssetRepository $assetRepository, StatusRepository $statusRepository)
    {
        $this->assetRepository = $assetRepository;
        $this->statusRepository = $statusRepository;
    }

    public function getAssetStatuses()
    {
        return $this->statusRepository->getAssetStatuses();
    }

    /**
     * Update status of an asset
     * 
     * @param $request - ajax request data
     * @param $id - asset id
     */
    public function updateStatus($request, $id)
    {
        // Initialize user_id and location_id variables
        $userId = NULL;
        $locationId = $request->assetLocationEdit;
        
        if ($request->assetStatusChange == 2) {
            $userId = $request->assetLocationEdit;
            $locationId = NULL;
        }

        $updateStatusData = [
            'status' => $request->assetStatusChange,
            'location_id' => $locationId,
            'user_id' => $userId,
        ];

        return $this->assetRepository->updateStatus($id, $updateStatusData);
    }

}