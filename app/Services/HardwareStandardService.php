<?php

namespace App\Services;

use App\Models\HardwareStandard;
use App\Repositories\HardwareStandardRepository;

class HardwareStandardService
{
    protected $hardwareStandardRepository;

    public function __construct(HardwareStandardRepository $hardwareStandardRepository)
    {
        $this->hardwareStandardRepository = $hardwareStandardRepository;
    }

    /**
    * Get list of Hardware Standards
    * 
    */
    public function showHardwareStandard()
    {
        return $this->hardwareStandardRepository->showHardwareStandard();
    }

    /**
     * Add Hardware Standard to storage
     *
     * @param $request - form request data
     */
    public function addHardwareStandard($request)
    {
        $validatedData = $request->validate([
            'assetHardwareStandard' => 'required|string|max:255',
            'assetType' => 'required|string|max:255',
        ]);

        $data = ['description' => $request->assetHardwareStandard
        , 'type_id' => $request->assetType];

        return $this->hardwareStandardRepository->addHardwareStandard($data);
    }

    /**
     * Delete Hardware Standard from storage
     *
     * @param $hardwareStandard - hardware standard object
     */
    public function deleteHardwareStandard($hardwareStandard)
    {
        return $this->hardwareStandardRepository->deleteHardwareStandard($hardwareStandard);
    }

    /**
     * Update Hardware Standard to storage
     *
     * @param $request - form request data
     * @param $hardwareStandard - hardware standard object
     */
    public function updateHardwareStandard($request, $hardwareStandard)
    {
        $hardwareStandardData = [
            'description' => $request->editHardwareStandard,
            'type_id' => $request->assetTypeEdit,
        ];

        return $type = $this->hardwareStandardRepository->updateHardwareStandard($hardwareStandard, $hardwareStandardData);
    }

}
