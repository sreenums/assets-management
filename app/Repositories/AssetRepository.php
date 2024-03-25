<?php

namespace App\Repositories;

use App\Models\Asset;
use Illuminate\Support\Facades\Log;

class AssetRepository
{
    protected $model;

    public function __construct(Asset $asset)
    {
        $this->model = $asset;
    }

    public function createAsset($data)
    {
        return $this->model->create($data);
    }

    public function updateAsset($asset, $locationData)
    {
        return $asset->update($locationData);
    }

    public function getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation()
    {
        return Asset::with(['type:id,type', 'hardwareStandard:id,description','technicalSpecification:id,description','location:id,name']);
    }

    public function FilterAsset($request, $assets)
    {

        if ($request->has('search')) {
            $searchTerm = $request->search['value'];
            if(isset($searchTerm)){
                $assets->where(function ($q) use ($searchTerm) {
                    $q->where('asset_tag', 'like', "%$searchTerm%")
                        ->orWhere('serial_no', 'like', "%$searchTerm%");
                });
            }
        }

        // if ($request->has('author') && $request->author != 'all') {
        //     $assets->where('user_id', $request->author);
        // }

        // //Filtering based on status
        // if ($request->has('status')) {
        //     if(request('status') == 1){ 
        //         $assets->where('is_active', 1); 
        //     }
        //     elseif(request('status') == 0){ 
        //         $assets->where('is_active', 0); 
        //     }
        // }

        // //Filtering based on comments count
        // if ($request->has('commentsCount') && isset($request->commentsCount)) {
        //     $assets->having('comments_count', '=', $request->commentsCount);
        // }

        // // Sorting based on ID column
        // if ($request->has('order')) {
        //     $orderColumnIndex = $request->order[0]['column'];
        //     $orderDirection = $request->order[0]['dir'];
        //     $orderColumnName = $request->columns[$orderColumnIndex]['data'];

        //     if ($orderColumnName === 'id') {
        //         $assets->orderBy('id', $orderDirection);
        //     }
        // }

        return $assets;
    }

    public function updateStatus($id, $updateStatusData)
    {
        $asset = Asset::findOrFail($id);

        return $asset->update($updateStatusData);
    }

    public function getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($assetId)
    {
        return $this->model->with(['type:id,type', 'hardwareStandard:id,description','technicalSpecification:id,description','location:id,name'])->findOrFail($assetId);
    }

    public function deleteAsset($asset)
    {
        return $asset->delete();
    }

    public function loadAsset($post)
    {
        return $post->load('user')->load('type')->load('hardwareStandard');
    }
}