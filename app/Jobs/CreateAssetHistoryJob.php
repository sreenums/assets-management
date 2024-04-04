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
use App\Services\AssetHistoryService;
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
    protected $changedFieldsUpdate;
    protected $updateOriginalFields;
    protected $assetHistoryService;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset, $authUserId, $action, $changedFieldsUpdate, $updateOriginalFields,AssetHistoryService $assetHistoryService)
    {
        $this->asset = $asset;
        $this->authUserId = $authUserId;
        $this->action = $action;
        $this->changedFieldsUpdate = $changedFieldsUpdate;
        $this->updateOriginalFields = $updateOriginalFields;
        $this->assetHistoryService = $assetHistoryService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {       
        //For asset create
        if($this->action == 'created'){

            $user = User::select('id', 'name')->findOrFail($this->authUserId);
            $description = "Asset created by $user->name";

            AssetHistory::create([
                'asset_id' => $this->asset->id,
                'action' => $this->action,
                'status_to' => $this->asset->status,
                'description' => $description,
                'user_id' => $this->authUserId,
                'changed_fields' => null,
            ]);

        }else{

            $changedFields = $this->changedFieldsUpdate;
            $originalFields = $this->updateOriginalFields;

            // Remove the 'updated_at' field, already exists in table
            unset($changedFields['updated_at']);
    
            if (!empty($changedFields)) {
    
                $description = NULL;

                $description = $this->assetHistoryService->generateDescriptionForJob($changedFields, $originalFields, $this->asset, $this->authUserId);
    
                $assetHistoryData = [
                    'asset_id' => $this->asset->id,
                    'action' => 'updated',
                    'description' => $description,
                    'user_id' => $this->authUserId,
                    'changed_fields' => json_encode($changedFields),
                ];
    
                if (array_key_exists('status', $changedFields)) {
                    $assetHistoryData['status_from'] = $originalFields['status'];
                    $assetHistoryData['status_to'] = $this->asset->status;
                }
    
                AssetHistory::create($assetHistoryData);
            }
        }

    }
    
}
