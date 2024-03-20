<?php

namespace App\Http\Controllers;

use App\Models\HardwareStandard;
use App\Services\AssetService;
use Illuminate\Http\Request;

class HardwareStandardController extends Controller
{

    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Display a listing of hardware standards.
     */
    public function index()
    {
        $hardwareStandards = $this->assetService->showHardwareStandard();
        $assetTypes = $this->assetService->showAssetTypes();
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
        $type = $this->assetService->addHardwareStandard($request);
        
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
        $this->assetService->updateHardwareStandard($request, $hardware_standard);

        return response()->json(['message' => 'Type has been updated successfully!']);
    }

    /**
     * Remove the hardware standard from storage.
     * 
     * @param $hardware_standard - hardware standard object.
     */
    public function destroy(HardwareStandard $hardware_standard)
    {
        $this->assetService->deleteHardwareStandard($hardware_standard);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
}
