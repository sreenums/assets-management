<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\HardwareStandard;
use App\Models\Location;
use App\Models\TechnicalSpecifications;
use App\Models\Type;
use App\Models\User;

class AssetObserver
{
    /**
     * Handle the Asset "created" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function created(Asset $asset)
    {
        $user = User::select('id', 'name')->findOrFail(auth()->id());
        $description = "Asset created by $user->name";

        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            'description' => $description,
            'user_id' => auth()->id(), // id from authentication set up
            'changed_fields' => null, 
        ]);
    }

    /**
     * Handle the Asset "updated" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function updated(Asset $asset)
    {
        $changedFields = $asset->getChanges();

        // Remove the 'updated_at' field from the array if it exists
        unset($changedFields['updated_at']);

        if (!empty($changedFields)) {

            $description = NULL; $statusDescription = '';

            // Capture the status before and after the update
            $oldStatus = $asset->getOriginal('status');
            $newStatus = $asset->status;

                // Check if the "status" field is among the changed fields
                if (array_key_exists('status', $changedFields)) {

                    $statusLabels = [
                        1 => 'Brand new',
                        2 => 'Assigned',
                        3 => 'Damaged',
                    ];

                    // Set the status label values
                    $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
                    $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;
                    $statusDescription ="Status changed from '$oldStatusLabel' to  '$newStatusLabel'";
                }

                $userIds = [auth()->id(), $asset->user_id];
                $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();

                $userOrlocation = '';
                if($newStatus == 2){
                    $userName = $users[$asset->user_id];
                    $userOrlocation = ", assigned user : $userName";
                }else{
                    $location = Location::findOrFail($asset->location_id);
                    $userOrlocation = ", location : $location->name";
                }

                $updatedBy = '';
                if(auth()->id()){
                    $updateUserName = $users[auth()->id()];
                    $updatedBy = ", Updated by : $updateUserName";
                }

                $otherDescriptions = '';
                //Check for change in asset type
                if (array_key_exists('type_id', $changedFields)) {
                    $oldType = $asset->getOriginal('type_id');
                    $newType = $asset->type_id;

                    $assetTypeIds = [$oldType, $newType];
                    $assetTypes = Type::whereIn('id', $assetTypeIds)->pluck('type', 'id')->toArray();

                    $otherDescriptions = ", Asset type changed from '$assetTypes[$oldType]' to '$assetTypes[$newType]'";
                }

                //Check for change in hardware standard
                if (array_key_exists('hardware_standard_id', $changedFields)) {
                    $oldHardwareStandard = $asset->getOriginal('hardware_standard_id');
                    $newHardwareStandard = $asset->hardware_standard_id;

                    $hardwareStandardIds = [$oldHardwareStandard, $newHardwareStandard];
                    $hardwareStandards = HardwareStandard::whereIn('id', $hardwareStandardIds)->pluck('description', 'id')->toArray();

                    $otherDescriptions = $otherDescriptions.", Hardware standard changed from '$hardwareStandards[$oldHardwareStandard]' to '$hardwareStandards[$newHardwareStandard]'";
                }

                //Check for change in technical specification
                if (array_key_exists('technical_specification_id', $changedFields)) {
                    $oldTechnicalSpec = $asset->getOriginal('technical_specification_id');
                    $newTechnicalSpec = $asset->technical_specification_id;

                    $technicalSpecIds = [$oldTechnicalSpec, $newTechnicalSpec];
                    $technicalSpecs = TechnicalSpecifications::whereIn('id', $technicalSpecIds)->pluck('description', 'id')->toArray();

                    $otherDescriptions = $otherDescriptions.", Technical Specification changed from '$technicalSpecs[$oldTechnicalSpec]' to '$technicalSpecs[$newTechnicalSpec]'";
                }

                //Check for change in asset tag
                if (array_key_exists('asset_tag', $changedFields)) {
                    $oldAssetTag = $asset->getOriginal('asset_tag');
                    $newAssetTag = $asset->asset_tag;

                    $otherDescriptions = $otherDescriptions.", Asset tag changed from '$oldAssetTag' to '$newAssetTag'";
                }

                //Check for change in serial number
                if (array_key_exists('serial_no', $changedFields)) {
                    $oldAssetSlno = $asset->getOriginal('serial_no');
                    $newAssetSlno = $asset->serial_no;

                    $otherDescriptions = $otherDescriptions.", Asset serail number changed from '$oldAssetSlno' to '$newAssetSlno'";
                }

                //Check for change in purchase order
                if (array_key_exists('purchase_order', $changedFields)) {
                    $oldPurchaseOrder = $asset->getOriginal('purchase_order');
                    $newPurchaseOrder = $asset->purchase_order;

                    $otherDescriptions = $otherDescriptions.", Asset purchase order number changed from '$oldPurchaseOrder' to '$newPurchaseOrder'";
                }

                $description = "$statusDescription $otherDescriptions $userOrlocation $updatedBy";

                $description = ltrim($description,', ');


            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'updated',
                'description' => $description,
                'user_id' => auth()->id(), // Assuming you have authentication set up
                'changed_fields' => json_encode($changedFields),
            ]);
        }
    }

    /**
     * Handle the Asset "deleted" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    // public function deleted(Asset $asset)
    // {
    //     AssetHistory::create([
    //         'asset_id' => $asset->id,
    //         'action' => 'deleted',
    //         //'user_id' => auth()->id(), // Assuming you have authentication set up
    //         'changed_fields' => null, // No need to store changed fields for deletion
    //     ]);
    // }

}
