<?php

namespace App\Services;

use App\Repositories\AssetTypeRepository;

class AssetTypeService
{
    protected $assetTypeRepository;

    public function __construct(AssetTypeRepository $assetTypeRepository)
    {
        $this->assetTypeRepository = $assetTypeRepository;
    }

    /**
     * Show Assets types
     * 
     */
    public function showAssetTypes()
    {
       return $this->assetTypeRepository->showAssetTypes();
    }
    
    /**
     * Add Assets type to the storage
     * 
     * @param $request - form request data
     */
    public function addType($request)
    {
        $validatedData = $request->validate([
            'assetType' => 'required|string|max:255',
        ]);

        $data = ['type' => $request->assetType];

        return $this->assetTypeRepository->addType($data);
    }

    /**
     * Update Asset type
     * 
     * @param $request - form request data
     * @param $type - asset type object
     */
    public function updateType($request, $type)
    {
        $typeData = [
            'type' => $request->editAssetType,
        ];

        return $type = $this->assetTypeRepository->updateType($type, $typeData);
    }

    /**
     * Delete Asset type
     * 
     * @param $type - asset type object
     */
    public function deleteType($type)
    {
        return $this->assetTypeRepository->deleteType($type);
    }
}