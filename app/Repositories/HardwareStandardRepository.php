<?php

namespace App\Repositories;

use App\Models\HardwareStandard;
use Illuminate\Support\Facades\Log;

class HardwareStandardRepository
{
    protected $model;

    public function __construct(HardwareStandard $hardwareStandard,)
    {
        $this->model = $hardwareStandard;
    }

    /**
     * Hardware Standards
     */

     public function showHardwareStandard()
     {
         return $this->model->get();
     }
 
     public function addHardwareStandard($data)
     {
         return $this->model->create($data);
     }

     public function updateHardwareStandard($hardwareStandard, $hardwareStandardData)
     {
         return $hardwareStandard->update($hardwareStandardData);
     }

     public function deleteHardwareStandard($hardwareStandard)
     {
        $hardwareStandard->technicalSpecification()->delete();
        return $hardwareStandard->delete();
     }


     public function getHardwareStandardWithType($request)
     {
        return $this->model->where('type_id', $request->assetType)->get();
     }

}