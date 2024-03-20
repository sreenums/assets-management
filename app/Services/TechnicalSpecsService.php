<?php

namespace App\Services;

use App\Repositories\TechnicalSpecsRepository;

class TechnicalSpecsService
{
    protected $technicalSpecsRepository;

    public function __construct(TechnicalSpecsRepository $technicalSpecsRepository)
    {
        $this->technicalSpecsRepository = $technicalSpecsRepository;
    }

    /**
     * Get list of Technical Specs
     */
    public function showTechnicalSpecs()
    {
       return $this->technicalSpecsRepository->showTechnicalSpecs();
    }

    /**
     * Add Technical Specification to storage
     * 
     * @param $request - form request data
     */
    public function addTechnicalSpec($request)
    {
       $validatedData = $request->validate([
           'tecnicalSpec' => 'required|string|max:255',
       ]);

       $data = [
        'description' => $request->tecnicalSpec,
        'hardware_standard_id' => $request->hardwareStandard,
        ];

       return $this->technicalSpecsRepository->addTechnicalSpec($data);
    }

    /**
     * Delete Technical Specification from storage
     * 
     * @param $hardwareStandard - Hardware standard object
     */
    public function deleteTechnicalSpec($hardwareStandard)
    {
        return $this->technicalSpecsRepository->deleteTechnicalSpec($hardwareStandard);
    }

    /**
     * Update Technical Specification to storage
     * 
     * @param $request - form request data
     * @param $technical_specs - Technical Specification object
     */
    public function updateTechnicalSpec($request, $technical_specs)
    {
        $validatedData = $request->validate([
            'editTechnicalSpec' => 'required|string|max:255',
        ]);
        
        $technicalSpecData = [
            'description' => $request->editTechnicalSpec,
            'hardware_standard_id' => $request->editHardwareStandard,
        ];

        return $type = $this->technicalSpecsRepository->updateTechnicalSpec($technical_specs, $technicalSpecData);
    }

}