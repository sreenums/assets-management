<?php

namespace App\Repositories;

use App\Models\AssetManagement;

class AssetManagementRepository
{
    protected $model;

    public function __construct(AssetManagement $assetManagement)
    {
        $this->model = $assetManagement;
    }

    public function createAsset($data)
    {
        return $this->model->create($data);
    }

}