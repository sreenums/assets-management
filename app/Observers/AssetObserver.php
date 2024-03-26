<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Location;
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
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            //'user_id' => auth()->id(), // Assuming you have authentication set up
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

        // Remove the 'updated_at' field from the array if it exists
        unset($changedFields['updated_at']);

        if (!empty($changedFields)) {

            $description = NULL;
            // Check if the "status" field is among the changed fields
            if (array_key_exists('status', $changedFields)) {
                // Capture the status before and after the update
                $oldStatus = $asset->getOriginal('status');
                $newStatus = $asset->status;

                $statusLabels = [
                    1 => 'Brand new',
                    2 => 'Assigned',
                    3 => 'Damaged',
                ];
                $userOrlocation = '';
                if($newStatus == 2){
                    $user = User::findOrFail($asset->user_id);
                    $userOrlocation = ", assigned to user : $user->name";
                }else{
                    $location = Location::findOrFail($asset->location_id);
                    $userOrlocation = ", location : $location->name";
                }

                // Set the status label values
                $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
                $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;

                $description = "Status changed from '$oldStatusLabel' to  '$newStatusLabel' $userOrlocation";

            }
            
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'updated',
                'description' => $description,
                //'user_id' => auth()->id(), // Assuming you have authentication set up
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
