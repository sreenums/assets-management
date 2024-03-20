<?php

namespace App\Repositories;

use App\Models\TechnicalSpecifications;
use Illuminate\Support\Facades\Log;

class TechnicalSpecsRepository
{
    protected $model;

    public function __construct(TechnicalSpecifications $technicalSpecification,)
    {
        $this->model = $technicalSpecification;
    }

    /**
     * List Technical Specs
     * 
     */

     public function showTechnicalSpecs()
     {
         return $this->model->get();
     }
 
     /**
      * Add Technical Specification to storage
      *
      * @param $data - form request data
      */
     public function addTechnicalSpec($data)
     {
        return $this->model->create($data);
     }

     /**
      * Update technical Specification to storage
      *
      * @param $technicalSpecification - Technical Specification object
      * @param $technicalSpecData - form request data
      */
     public function updateTechnicalSpec($technicalSpecification, $technicalSpecData)
     {
         return $technicalSpecification->update($technicalSpecData);
     }

     /**
      * Delete Technical Specification from storage
      *
      * @param $technicalSpecification - Technical Specification object
      */
     public function deleteTechnicalSpec($technicalSpecification)
     {
         return $technicalSpecification->delete();
     }

     /**
      * Get list of Technical Specs with a specific Hardware Standard
      *
      * @param $request - form request data
      */
     public function getTechnicalSpecsWithHardwareStandard($request)
     {
        return $this->model->where('hardware_standard_id', $request->hardwareStandard)->get();
     }

}