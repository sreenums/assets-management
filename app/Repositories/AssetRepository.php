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

    public function insertAssetUpload($data)
    {
        return $this->model->insert($data);
    }

    public function getAssetsList()
    {
        return Asset::with(['type:id,type', 'hardwareStandard:id,description','technicalSpecification:id,description','location:id,name']);
    }

    public function FilterAsset($request, $assets)
    {
        //For search with asset tag and serial number
        if ($request->has('assetSearch')) {
            $searchTerm = $request->assetSearch;
            if(isset($searchTerm)){
                $assets->where(function ($q) use ($searchTerm) {
                    $q->where('asset_tag', 'like', "%$searchTerm%")
                        ->orWhere('serial_no', 'like', "%$searchTerm%");
                });
            }
        }
        
        //Filtering based on type
        if ($request->has('assetType') && $request->assetType != 'all') {
            $assets->where('type_id', $request->assetType);
        }

        //Filtering based on hardware standard
        if ($request->has('hardwareStandard') && $request->hardwareStandard != 'all') {
            $assets->where('hardware_standard_id', $request->hardwareStandard);
        }

        //Filtering based on technical specification
        if ($request->has('technicalSpec') && $request->technicalSpec != 'all') {
            $assets->where('technical_specification_id', $request->technicalSpec);
        }

        //Filtering based on status
        if ($request->has('status') && $request->status != 'all') {
            $assets->where('status', $request->status);
        }

        $ageFilter = now();
        //Age filtering based on days
        // if($request->has('days') && $request->days != '') {
        //     $ageFilter = $ageFilter->subDays($request->days);
        // }
        // //Age filtering based on months
        // if($request->has('months') && $request->months != '') {
        //     $ageFilter = $ageFilter->subMonths($request->months);
        // }
        // //Age filtering based on years
        // if($request->has('years') && $request->years != '') {
        //     $ageFilter = $ageFilter->subYears($request->years);
        // }
        
        //Age filtering based on years
        if($request->has('periodSearch') && $request->periodSearch != '') {
            if($request->has('periodFilter')){
                if($request->periodFilter == 'years'){
                    $ageFilter = $ageFilter->subYears($request->periodSearch);
                }else if($request->periodFilter == 'months'){
                    $ageFilter = $ageFilter->subMonths($request->periodSearch);
                }else{
                    $ageFilter = $ageFilter->subDays($request->periodSearch);
                }
            }
            $assets->where('created_at', '>=', $ageFilter);
            //$ageFilter = $ageFilter->subYears($request->years);
        }
        
        // if($request->has('days') || $request->has('months') || $request->has('years')) {
        //     $assets->where('created_at', '>=', $ageFilter);
        // }

        // Sorting based on ID column
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];

            if ($orderColumnName === 'id') {
                $assets->orderBy('id', $orderDirection);
            }
        }

        // Log the generated SQL query
        $sql = $assets->toSql();
        $bindings = $assets->getBindings();
        Log::info('FilterAsset - SQL:', ['sql' => $sql, 'bindings' => $bindings]);

        return $assets;
    }

    public function updateStatus($id, $updateStatusData)
    {
        $asset = Asset::findOrFail($id);

        return $asset->update($updateStatusData);
    }

    public function getAsset($assetId)
    {
        return $this->model->with(['type:id,type', 'hardwareStandard:id,description','technicalSpecification:id,description','location:id,name'])->findOrFail($assetId);
    }

    public function deleteAsset($asset)
    {
        $asset->assetHistories()->delete();
        return $asset->delete();
    }

    public function loadAsset($post)
    {
        return $post->load('user')->load('type')->load('hardwareStandard');
    }
}