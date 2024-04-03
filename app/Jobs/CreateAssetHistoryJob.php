<?php

namespace App\Jobs;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\HardwareStandard;
use App\Models\Location;
use App\Models\Status;
use App\Models\TechnicalSpecifications;
use App\Models\Type;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAssetHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $authUserId;
    protected $action;
    protected $description;
    protected $changedFieldsUpdate;
    protected $updateOriginalFields;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset, $authUserId, $action, $description, $changedFieldsUpdate, $updateOriginalFields)
    {
        $this->asset = $asset;
        $this->authUserId = $authUserId;
        $this->action = $action;
        $this->description = $description;
        $this->changedFieldsUpdate = $changedFieldsUpdate;
        $this->updateOriginalFields = $updateOriginalFields;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            if($this->action == 'created'){
                $user = User::select('id', 'name')->findOrFail($this->authUserId);

                AssetHistory::create([
                    'asset_id' => $this->asset->id,
                    'action' => $this->action,
                    'status_to' => $this->asset->status,
                    'description' => $this->description,
                    'user_id' => $user->id,
                    'changed_fields' => null,
                ]);

            }else{
                //$changedFields = $this->asset->getChanges();
                $changedFields = $this->changedFieldsUpdate;
                $originalFields = $this->updateOriginalFields;

                // Remove the 'updated_at' field, already exists in table
                unset($changedFields['updated_at']);
        
                if (!empty($changedFields)) {
        
                    $description = NULL; $statusDescription = '';
        
                    // Capture the status before and after the update
                    $oldStatus = $originalFields['status'];
                    $newStatus = $this->asset->status;
        
                        // Check if the "status" field is among the changed fields
                        if (array_key_exists('status', $changedFields)) {
        
                            $statuses = Status::pluck('name', 'id')->toArray();
        
                            // Set the status label values
                            $oldStatusLabel = $statuses[$oldStatus] ?? $oldStatus;
                            $newStatusLabel = $statuses[$newStatus] ?? $newStatus;
                            $statusDescription ="Status changed from '$oldStatusLabel' to  '$newStatusLabel'";
        
                        }
        
                        $userIds = [$this->authUserId, $this->asset->user_id];
                        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();
        
                        //For change in user or location
                        $userOrlocation = '';
                        $assigned = config('custom.status.assigned');
                        if($newStatus == $assigned){
                            $userName = $users[$this->asset->user_id];
                            $userOrlocation = ", assigned user : $userName";
                        }else{
                            $location = Location::findOrFail($this->asset->location_id);
                            $userOrlocation = ", location : $location->name";
                        }
        
                        $otherDescriptions = '';
                        //Check for change in asset type
                        if (array_key_exists('type_id', $changedFields)) {
                            $oldType = $originalFields['type_id'];
                            $newType = $this->asset->type_id;
        
                            $assetTypeIds = [$oldType, $newType];
                            $assetTypes = Type::whereIn('id', $assetTypeIds)->pluck('type', 'id')->toArray();
        
                            $otherDescriptions = ", Asset type changed from '$assetTypes[$oldType]' to '$assetTypes[$newType]'";
                        }
        
                        //Check for change in hardware standard
                        if (array_key_exists('hardware_standard_id', $changedFields)) {
                            $oldHardwareStandard = $originalFields['hardware_standard_id'];
                            $newHardwareStandard = $this->asset->hardware_standard_id;
        
                            $hardwareStandardIds = [$oldHardwareStandard, $newHardwareStandard];
                            $hardwareStandards = HardwareStandard::whereIn('id', $hardwareStandardIds)->pluck('description', 'id')->toArray();
        
                            $otherDescriptions = $otherDescriptions.", Hardware standard changed from '$hardwareStandards[$oldHardwareStandard]' to '$hardwareStandards[$newHardwareStandard]'";
                        }
        
                        //Check for change in technical specification
                        if (array_key_exists('technical_specification_id', $changedFields)) {
                            $oldTechnicalSpec = $originalFields['technical_specification_id'];
                            $newTechnicalSpec = $this->asset->technical_specification_id;
        
                            $technicalSpecIds = [$oldTechnicalSpec, $newTechnicalSpec];
                            $technicalSpecs = TechnicalSpecifications::whereIn('id', $technicalSpecIds)->pluck('description', 'id')->toArray();
        
                            $otherDescriptions = $otherDescriptions.", Technical Specification changed from '$technicalSpecs[$oldTechnicalSpec]' to '$technicalSpecs[$newTechnicalSpec]'";
                        }
        
                        //Check for change in asset tag
                        if (array_key_exists('asset_tag', $changedFields)) {
                            $oldAssetTag = $originalFields['asset_tag'];
                            $newAssetTag = $this->asset->asset_tag;
        
                            $otherDescriptions = $otherDescriptions.", Asset tag changed from '$oldAssetTag' to '$newAssetTag'";
                        }
        
                        //Check for change in serial number
                        if (array_key_exists('serial_no', $changedFields)) {
                            $oldAssetSlno = $originalFields['serial_no'];
                            $newAssetSlno = $this->asset->serial_no;
        
                            $otherDescriptions = $otherDescriptions.", Asset serail number changed from '$oldAssetSlno' to '$newAssetSlno'";
                        }
        
                        //Check for change in purchase order
                        if (array_key_exists('purchase_order', $changedFields)) {
                            $oldPurchaseOrder = $originalFields['purchase_order'];
                            $newPurchaseOrder = $this->asset->purchase_order;
        
                            $otherDescriptions = $otherDescriptions.", Asset purchase order number changed from '$oldPurchaseOrder' to '$newPurchaseOrder'";
                        }
       
                        $updatedBy = '';
                        if($this->authUserId){
                            $updateUserName = $users[$this->authUserId];
                            $updatedBy = ", Updated by : $updateUserName";
                        }
        
                        $description = "$statusDescription $otherDescriptions $userOrlocation $updatedBy";
        
                        $description = ltrim($description,', ');
        
                    $assetHistoryData = [
                        'asset_id' => $this->asset->id,
                        'action' => 'updated',
                        'description' => $description,
                        'user_id' => $this->authUserId,
                        'changed_fields' => json_encode($changedFields),
                    ];
        
                    if (array_key_exists('status', $changedFields)) {
                        $assetHistoryData['status_from'] = $oldStatus;
                        $assetHistoryData['status_to'] = $this->asset->status;
                    }
        
                    AssetHistory::create($assetHistoryData);
                }
            }

    }
}
