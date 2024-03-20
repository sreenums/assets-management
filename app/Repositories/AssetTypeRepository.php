<?php

namespace App\Repositories;

use App\Models\HardwareStandard;
use App\Models\Type;
use Illuminate\Support\Facades\Log;

class AssetTypeRepository
{
    protected $model;

    public function __construct(Type $type,)
    {
        $this->model = $type;
    }

    public function showAssetTypes()
    {
        return $this->model->get();
    }

    public function addType($data)
    {
        return $this->model->create($data);
    }

    public function updateType($type, $typeData)
    {
        return $type->update($typeData);
    }

    public function deleteType($type)
    {
        $type->technicalSpecifications()->delete();
        $type->hardwareStandard()->delete();
        return $type->delete();
    }


}