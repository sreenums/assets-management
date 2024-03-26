<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\TechnicalSpecsRepository;
use App\Repositories\UserRepository;
use App\Repositories\LocationRepository;

class AssetService
{
    protected $assetRepository;
    protected $hardwareStandardRepository;
    protected $technicalSpecsRepository;
    protected $userRepository;
    protected $locationRepository;

    public function __construct(AssetRepository $assetRepository, HardwareStandardRepository $hardwareStandardRepository, TechnicalSpecsRepository $technicalSpecsRepository,UserRepository $userRepository,LocationRepository $locationRepository)
    {
        $this->assetRepository = $assetRepository;
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->technicalSpecsRepository = $technicalSpecsRepository;
        $this->userRepository = $userRepository;
        $this->locationRepository = $locationRepository;
    }


    public function updateAsset($request, $asset)
    {
        // Initialize user_id and location_id variables
        $userId = NULL;
        $locationId = $request->assetLocation;
        //dd($request->assetStatus);
        // Check if status is 2
        if ($request->assetStatus == 2) {
            $userId = $request->assetLocation;
            $locationId = NULL;
        }
        
        $assetData = [
            'type_id' => $request->assetType,
            'hardware_standard_id' => $request->hardwareStandard,
            'technical_specification_id' => $request->technicalSpec,
            'location_id' => $locationId,
            'user_id' => $userId,
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
     * @param $request - ajax request data(assetType)
     */
    public function getHardwareStandardWithType($request)
    {
        return $this->hardwareStandardRepository->getHardwareStandardWithType($request);
    }

    /**
     * Get list of technical specs with hardware standard
     * 
     * @param $request - ajax request data
     */
    public function getTechnicalSpecsWithHardwareStandard($request)
    {
        return $this->technicalSpecsRepository->getTechnicalSpecsWithHardwareStandard($request);
    }

    /**
     * Get list of users
     */
    public function getUserLocationsList()
    {
        return $this->locationRepository->getUserLocationsList();
    }

    /**
     * Get list of locations
     */
    public function getLocations()
    {
        return $this->locationRepository->getLocations();
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

    public function deleteAsset($asset)
    {
        return $this->assetRepository->deleteAsset($asset);
    }

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

            $location = '';
            if ($asset->status == 2) {
                $location = $asset->user->name;
            } else {
                $location = $asset->location->name;
            }

            return [
                'id' => $asset->id,
                'type' => $asset->type->type,
                'hardware_standard' => $asset->hardwareStandard->description,
                'technicalSpecification' => $asset->technicalSpecification->description,
                'location' => $location,
                'assetTag' => $asset->asset_tag,
                'status' => $asset->status_text,
            ];
        });
    }

    public function getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($assetId)
    {
        return $this->assetRepository->getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($assetId);
    }

    /**
     * For asset show
     */
    public function loadAsset($asset)
    {
        return $this->assetRepository->loadAsset($asset);
    }   
}