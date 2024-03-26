<?php

namespace App\Http\Controllers;

use App\Models\TechnicalSpecifications;
use App\Services\TechnicalSpecsService;
use App\Services\HardwareStandardService;
use App\Services\AssetParameterService;
use Illuminate\Http\Request;

class TechnicalSpecificationsController extends Controller
{

    protected $technicalSpecsService;
    protected $assetParameterService;
    protected $hardwareStandardService;

    /**
     * Constructs a new instance of the class.
     *
     * @param TechnicalSpecsService $technicalSpecsService The technical specifications service.
     * @param assetParameterService $assetParameterService The asset service.
     */
    public function __construct(TechnicalSpecsService $technicalSpecsService, HardwareStandardService $hardwareStandardService)
    {
        $this->technicalSpecsService = $technicalSpecsService;
        $this->hardwareStandardService = $hardwareStandardService;
    }

    /**
     * Display a listing of technical specifications.
     */
    public function index()
    {
        $technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $hardwareStandards = $this->hardwareStandardService->showHardwareStandard();
        
        return view('technical-spec-home', compact('technicalSpecs','hardwareStandards'));
    }

    /**
     * Store the newly created technical specification.
     * 
     * @param $request form request data
     */
    public function store(Request $request)
    {
        $type = $this->technicalSpecsService->addTechnicalSpec($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->tecnicalSpec, 'id' => $type->id, 'hardwareId' => $type->hardware_standard_id ]);
    }

    /**
     * Update the technical specification in storage.
     * 
     * @param $request form request data
     * @param $technicalSpec - technical specification object
     */
    public function update(Request $request, TechnicalSpecifications $technicalSpec)
    {
        $this->technicalSpecsService->updateTechnicalSpec($request, $technicalSpec);

        return response()->json(['message' => 'Type has been updated successfully!']);
    }

    /**
     * Remove the specified technical specification from storage.
     * 
     * @param $technicalSpec - technical specification object.
     */
    public function destroy(TechnicalSpecifications $technicalSpec)
    {
        $this->technicalSpecsService->deleteTechnicalSpec($technicalSpec);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
}
