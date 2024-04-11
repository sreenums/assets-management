<?php

namespace App\Observers;

use App\Jobs\CreateAssetHistoryJob;
use App\Jobs\UpdateAssetHistoryJob;
use App\Services\AssetHistoryService;
use App\Models\Asset;

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
        $authUserId = auth()->id();
        $assetHistoryService = app(AssetHistoryService::class);
    
        // Dispatch the job to create asset history
        CreateAssetHistoryJob::dispatch($asset, $authUserId, $assetHistoryService);
    }

    /**
     * Handle the Asset "updated" event.
     *
     * @param  \App\Models\Asset  $asset
     * @return void
     */
    public function updated(Asset $asset)
    {
        $authUserId = auth()->id();
        $changedFieldsUpdate = $asset->getChanges();
        $updateOriginalFields = $asset->getOriginal();
        $assetHistoryService = app(AssetHistoryService::class);

        // Dispatch the job to update asset history
        UpdateAssetHistoryJob::dispatch($asset, $authUserId, $changedFieldsUpdate, $updateOriginalFields, $assetHistoryService);
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
