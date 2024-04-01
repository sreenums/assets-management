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

class CreateAssetHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $action;
    protected $description;

    /**
     * Create a new job instance.
     */
    public function __construct(Asset $asset, $action, $description)
    {
        $this->asset = $asset;
        $this->action = $action;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            $user = User::select('id', 'name')->findOrFail(auth()->id());

            AssetHistory::create([
                'asset_id' => $this->asset->id,
                'action' => $this->action,
                'status_to' => $this->asset->status,
                'description' => $this->description,
                'user_id' => $user->id,
                'changed_fields' => null,
            ]);

    }
}
