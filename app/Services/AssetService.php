<?php

namespace App\Services;

use App\Repositories\AssetRepository;

class AssetService
{
    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    /**
     * Updates an asset with the provided data.
     *
     * @param mixed $request The request object containing the update asset data.
     * @param Asset $asset The asset to be updated.
     * @return Asset The updated asset.
     */
    public function updateAsset($request, $asset)
    {
        // Initialize user_id and location_id variables
        $userId = NULL;
        $locationId = $request->assetLocation;
        
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
     * Creates an asset using the provided request data.
     *
     * @param $request The request data for creating the asset.
     * @return mixed The created asset.
     */
    public function createAsset($request)
    {
        $assetData = $this->getAssetCreateData($request);
        return $this->assetRepository->createAsset($assetData);
    }

    /**
     * Generate the post data
     * 
     * @param $request Form data
     * @return array of data for creating asset
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

    /**
     * Deletes an asset using the asset repository.
     *
     * @param $asset The asset to be deleted.
     * @return bool True if the asset was successfully deleted, false otherwise.
     */
    public function deleteAsset($asset)
    {
        return $this->assetRepository->deleteAsset($asset);
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

    /**
     * Get the list of assets with type, hardware, standard, technical spec, status, and location.
     *
     */
    public function getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation()
    {
        return $this->assetRepository->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();
    }

    /**
     * Filter the asset based on the given request and assets.
     *
     * @param $request The request object.
     * @param $assets The assets to filter.
     * @return The filtered assets.
     */
    public function FilterAsset($request, $assets)
    {
        return $this->assetRepository->FilterAsset($request, $assets);
    }

    /**
     * Format data for data table
     * 
     * @param $assets - list of assets
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

    /**
     * Retrieves the asset with the specified ID, along with its type, hardware standard, technical specifications, status, and location.
     *
     * @param int $assetId The ID of the asset to retrieve.
     * @return The asset object with the specified ID and additional information.
     */
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