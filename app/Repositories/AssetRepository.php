<?php

namespace App\Repositories;

use App\Models\Asset;

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

    public function getAssetsListWithTypeHardwareStandardTechnicalSpecAndStatus()
    {
        return Asset::with(['type:id,type', 'hardwareStandard:id,description','technicalSpecification:id,description'])->get();
        //return Asset::with(['types:id,type', 'hardwareStandard:id,description', 'technicalSpecification:id,description'])->get();
    }

}