<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use DateTime;

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
     * Get the list of assets with type, hardware, standard, technical spec, status, and location.
     *
     */
    public function getAssetsList()
    {
        return $this->assetRepository->getAssetsList();
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

            $assigned = config('custom.status.assigned');
            
            $location = '';
            if ($asset->status == $assigned) {
                $location = $asset->user->name;
            } else {
                $location = $asset->location->name;
            }

            $ageDescription = $this->getAssetAge($asset->created_at);

            return [
                'id' => $asset->id,
                'type' => $asset->type->type,
                'hardware_standard' => $asset->hardwareStandard->description,
                'technicalSpecification' => $asset->technicalSpecification->description,
                'location' => $location,
                'age' => $ageDescription,
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
    public function getAsset($assetId)
    {
        return $this->assetRepository->getAsset($assetId);
    }

    /**
     * For asset show
     */
    public function loadAsset($asset)
    {
        return $this->assetRepository->loadAsset($asset);
    }

    /**
     * Generate age
     * 
     * @param $assetCreateDate - asset create date
     * @return age description
     */
    public function getAssetAge($assetCreateDate)
    {
        // Given date string to DateTime object
        $assetCreateDate = new DateTime($assetCreateDate);

        // Get current date as a DateTime object
        $currentDate = new DateTime();

        $ageDescription = '';
        $dateInterval = $assetCreateDate->diff($currentDate);
        $daysDifference = $dateInterval->days;
        if($daysDifference == 0) {
            $ageDescription = '1 Day';
        }
        if($daysDifference <= 30 && $daysDifference > 0){
            $ageDescription = $daysDifference.' days ago';
        }
        if($daysDifference > 30){
            $quotientValue = floor($daysDifference / 30);
            $ageDescription = $quotientValue.' months ago';
        }

        return $ageDescription;
    }
       
}