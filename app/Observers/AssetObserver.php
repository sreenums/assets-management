<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetHistory;

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
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'changed_fields' => null, // No need to store changed fields for creation
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

        if (!empty($changedFields)) {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'updated',
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
    public function deleted(Asset $asset)
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'deleted',
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'changed_fields' => null, // No need to store changed fields for deletion
        ]);
    }
}
