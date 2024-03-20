<?php

namespace App\Http\Controllers;

use App\Models\HardwareStandard;
use App\Services\AssetParameterService;
use Illuminate\Http\Request;

class HardwareStandardController extends Controller
{

    protected $assetParameterService;

    public function __construct(AssetParameterService $assetParameterService)
    {
        $this->assetParameterService = $assetParameterService;
    }

    /**
     * Display a listing of hardware standards.
     */
    public function index()
    {
        $hardwareStandards = $this->assetParameterService->showHardwareStandard();
        $assetTypes = $this->assetParameterService->showAssetTypes();
        //$hardwareStandard = HardwareStandard::getList();
        
        return view('hardware-home', compact('hardwareStandards','assetTypes'));
    }

    /**
     * Store a newly created hardware standard in storage.
     * 
     * @param $request form request data
     */
    public function store(Request $request)
    {
        $type = $this->assetParameterService->addHardwareStandard($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetHardwareStandard, 'id' => $type->id, 'type_id' => $type->type_id ]);
    }

    /**
     * Update the specified hardware standard in storage.
     * 
     * @param $request form request data
     * @param $hardware_standard - hardware standard object
     */
    public function update(Request $request, HardwareStandard $hardware_standard)
    {
        $this->assetParameterService->updateHardwareStandard($request, $hardware_standard);

        return response()->json(['message' => 'Type has been updated successfully!']);
    }

    /**
     * Remove the hardware standard from storage.
     * 
     * @param $hardware_standard - hardware standard object.
     */
    public function destroy(HardwareStandard $hardware_standard)
    {
        $this->assetParameterService->deleteHardwareStandard($hardware_standard);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
}
