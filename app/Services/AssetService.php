<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\TechnicalSpecsRepository;

class AssetService
{
    protected $assetRepository;
    protected $hardwareStandardRepository;
    protected $technicalSpecsRepository;

    public function __construct(AssetRepository $assetRepository, HardwareStandardRepository $hardwareStandardRepository, TechnicalSpecsRepository $technicalSpecsRepository)
    {
        $this->assetRepository = $assetRepository;
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->technicalSpecsRepository = $technicalSpecsRepository;
    }


    public function updateAsset($request, $asset)
    {
        
        $assetData = [
            'type_id' => $request->assetType,
            'hardware_standard_id' => $request->hardwareStandard,
            'technical_specification_id' => $request->technicalSpec,
            'location_id' => $request->assetLocation,
            'asset_tag' => $request->assetTag,
            'serial_no' => $request->serialNo,
            'purchase_order' => $request->purchasingOrder,
            'status' => $request->assetStatus,
        ];

        return $type = $this->assetRepository->updateAsset($asset, $assetData);
    }

    /**
     * Get list of hardware standards for an asset type
     * 
     * @param $request - form request data(assetType)
     */
    public function getHardwareStandardWithType($request)
    {
        return $this->hardwareStandardRepository->getHardwareStandardWithType($request);
    }

    public function getTechnicalSpecsWithHardwareStandard($request)
    {
        return $this->technicalSpecsRepository->getTechnicalSpecsWithHardwareStandard($request);
    }

    public function createAsset($request)
    {
        $assetData = $this->getAssetCreateData($request);
        return $this->assetRepository->createAsset($assetData);
    }

    /**
     * Generate the post data
     * 
     * @param $request Form data
     */
    public function getAssetCreateData($request)
    {   
        return [
            'type_id' => $request->assetType,
            'hardware_standard_id' => $request->hardwareStandard,
            'technical_specification_id' => $request->technicalSpec,
            'location_id' => $request->assetLocation,
            'asset_tag' => $request->assetTag,
            'serial_no' => $request->serialNo,
            'purchase_order' => $request->purchasingOrder,
            'status' => $request->assetStatus,
        ];
    }

    public function getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation()
    {
        return $this->assetRepository->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();
    }

    public function FilterAsset($request, $assets)
    {
        return $this->assetRepository->FilterAsset($request, $assets);
    }

    /**
     * Format data for data table
     */
    public function formatDataTable($assets)
    {
        return $assets->map(function($asset) {
            return [
                'id' => $asset->id,
                'type' => $asset->type->type,
                'hardware_standard' => $asset->hardwareStandard->description,
                'technicalSpecification' => $asset->technicalSpecification->description,
                'location' => $asset->location->name,
                'status' => $asset->status,
            ];
        });
    }

    // public function editAsset($assetId)
    // {
    //     return $this->assetRepository->editAsset($assetId);
    // }

    public function getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($assetId)
    {
        return $this->assetRepository->getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($assetId);
    }

}