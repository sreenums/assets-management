<?php

namespace App\Jobs;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Services\AssetHistoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAssetHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $authUserId;
    protected $changedFieldsUpdate;
    protected $updateOriginalFields;
    protected $assetHistoryService;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset, $authUserId, $changedFieldsUpdate, $updateOriginalFields,AssetHistoryService $assetHistoryService)
    {
        $this->asset = $asset;
        $this->authUserId = $authUserId;
        $this->changedFieldsUpdate = $changedFieldsUpdate;
        $this->updateOriginalFields = $updateOriginalFields;
        $this->assetHistoryService = $assetHistoryService;
    }

    /**
     * Execute the job for update asset history.
     */
    public function handle(): void
    {
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
