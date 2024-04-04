<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Status;
use App\Repositories\AssetHistoryRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\TechnicalSpecsRepository;
use App\Repositories\UserRepository;

class AssetHistoryService
{
    protected $assetHistoryRepository;
    protected $userRepository;
    protected $assetTypeRepository;
    protected $hardwareStandardRepository;
    protected $technicalSpecsRepository;

    public function __construct(AssetHistoryRepository $assetHistoryRepository, UserRepository $userRepository, AssetTypeRepository $assetTypeRepository, HardwareStandardRepository $hardwareStandardRepository, TechnicalSpecsRepository $technicalSpecsRepository)
    {
        $this->assetHistoryRepository = $assetHistoryRepository;
        $this->userRepository = $userRepository;
        $this->assetTypeRepository = $assetTypeRepository;
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->technicalSpecsRepository = $technicalSpecsRepository;
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

    /**
     * Generate description for asset history job
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     * @param $authUserId - logged in user id
     */
    public function generateDescriptionForJob($changedFields, $originalFields, $asset, $authUserId)
    {
        $description = NULL;
        $statuses = Status::pluck('name', 'id')->toArray();
        //$users = User::whereIn('id', [$authUserId, $asset->user_id])->pluck('name', 'id')->toArray();
        $users = $this->userRepository->getSelectedUsers($authUserId, $asset->user_id);
    
        $description = $this->getStatusDescription($changedFields, $originalFields, $statuses, $asset);
        $description .= $this->getUserOrLocationDescription($asset, $users);
        $description .= $this->getAssetTypeDescription($changedFields, $originalFields, $asset);
        $description .= $this->getHardwareStandardDescription($changedFields, $originalFields, $asset);
        $description .= $this->getTechnicalSpecDescription($changedFields, $originalFields, $asset);
        $description .= $this->getOtherDescriptions($changedFields, $originalFields, $asset);
    
        if ($authUserId) {
            $updateUserName = $users[$authUserId];
            $description .= ", Updated by: $updateUserName";
        }
    
        return ltrim($description, ', ');
    }

    /**
     * Get status description
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     */
    public function getStatusDescription($changedFields, $originalFields, $statuses, $asset)
    {
        if (array_key_exists('status', $changedFields)) {
            $oldStatusLabel = $statuses[$originalFields['status']] ?? $originalFields['status'];
            $newStatusLabel = $statuses[$asset->status] ?? $asset->status;
            return "Status changed from '$oldStatusLabel' to '$newStatusLabel'";
        }
        return '';
    }

    /**
     * Get user or location description
     * 
     * @param $asset - asset object
     * @param $users - array of users
     */
    public function getUserOrLocationDescription($asset, $users)
    {
        $assigned = config('custom.status.assigned');
        if ($asset->status == $assigned) {
            $userName = $users[$asset->user_id];
            return ", Assigned user: $userName";
        } else {
            $location = Location::findOrFail($asset->location_id);
            return ", Location: $location->name";
        }
    }

    /**
     * Get asset type description
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     */
    public function getAssetTypeDescription($changedFields, $originalFields, $asset)
    {
        if (array_key_exists('type_id', $changedFields)) {
            $oldType = $originalFields['type_id'];
            $newType = $asset->type_id;

            $assetTypeIds = [$oldType, $newType];
            //$assetTypes = Type::whereIn('id', $assetTypeIds)->pluck('type', 'id')->toArray();
            $assetTypes = $this->assetTypeRepository->getSelectedAssetTypes($assetTypeIds);

            return ", Asset type changed from '$assetTypes[$oldType]' to '$assetTypes[$newType]'";
        }
    }

    /**
     * Get hardware standard description
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     */
    public function getHardwareStandardDescription($changedFields, $originalFields, $asset)
    {
        if (array_key_exists('hardware_standard_id', $changedFields)) {
            $oldHardwareStandard = $originalFields['hardware_standard_id'];
            $newHardwareStandard = $asset->hardware_standard_id;

            $hardwareStandardIds = [$oldHardwareStandard, $newHardwareStandard];
            $hardwareStandards = $this->hardwareStandardRepository->getSelectedHardwareStandards($hardwareStandardIds);

            return ", Hardware standard changed from '$hardwareStandards[$oldHardwareStandard]' to '$hardwareStandards[$newHardwareStandard]'";
        }
    }

    /**
     * Get technical specification description
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     */
    public function getTechnicalSpecDescription($changedFields, $originalFields, $asset)
    {
        //Check for change in technical specification
        if (array_key_exists('technical_specification_id', $changedFields)) {
            $oldTechnicalSpec = $originalFields['technical_specification_id'];
            $newTechnicalSpec = $asset->technical_specification_id;

            $technicalSpecIds = [$oldTechnicalSpec, $newTechnicalSpec];
            $technicalSpecs = $this->technicalSpecsRepository->getSelctedTechnicalSpecs($technicalSpecIds);

            return ", Technical Specification changed from '$technicalSpecs[$oldTechnicalSpec]' to '$technicalSpecs[$newTechnicalSpec]'";
        }
    }

    /**
     * Get all other decriptions - asset tag, serial number, purchase order
     * 
     * @param $changedFields - array of changed fields
     * @param $originalFields - array of original fields
     * @param $asset - asset object
     */
    public function getOtherDescriptions($changedFields, $originalFields, $asset)
    {
        $otherDescriptions = '';
    
        //Check for change in asset tag
        if (array_key_exists('asset_tag', $changedFields)) {
            $oldAssetTag = $originalFields['asset_tag'];
            $newAssetTag = $asset->asset_tag;

            $otherDescriptions .= ", Asset tag changed from '$oldAssetTag' to '$newAssetTag'";
        }

        //Check for change in serial number
        if (array_key_exists('serial_no', $changedFields)) {
            $oldAssetSlno = $originalFields['serial_no'];
            $newAssetSlno = $asset->serial_no;

            $otherDescriptions .= ", Asset serail number changed from '$oldAssetSlno' to '$newAssetSlno'";
        }

        //Check for change in purchase order
        if (array_key_exists('purchase_order', $changedFields)) {
            $oldPurchaseOrder = $originalFields['purchase_order'];
            $newPurchaseOrder = $asset->purchase_order;

            $otherDescriptions .= ", Asset purchase order number changed from '$oldPurchaseOrder' to '$newPurchaseOrder'";
        }
    
        return $otherDescriptions;
    }

}