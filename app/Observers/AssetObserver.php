<?php

namespace App\Observers;

use App\Jobs\CreateAssetHistory;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\HardwareStandard;
use App\Models\Location;
use App\Models\Status;
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
        $authUserId = auth()->id();
        $user = User::select('id', 'name')->findOrFail($authUserId);
        $description = "Asset created by $user->name";
    
        // Dispatch the job to create asset history
        CreateAssetHistory::dispatch($asset, $authUserId, 'created', $description, NULL, NULL);
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
        CreateAssetHistory::dispatch($asset, $authUserId, 'updated', NULL, $changedFieldsUpdate, $updateOriginalFields);

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
