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
    protected $assetHistoryService;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset, $authUserId, AssetHistoryService $assetHistoryService)
    {
        $this->asset = $asset;
        $this->authUserId = $authUserId;
        $this->assetHistoryService = $assetHistoryService;
    }

    /**
     * Execute the job for add asset history.
     */
    public function handle(): void
    {       
        $user = User::select('id', 'name')->findOrFail($this->authUserId);
        $description = "Asset created by $user->name";

        AssetHistory::create([
            'asset_id' => $this->asset->id,
            'action' => 'created',
            'status_to' => $this->asset->status,
            'description' => $description,
            'user_id' => $this->authUserId,
            'changed_fields' => null,
        ]);
    }
    
}
