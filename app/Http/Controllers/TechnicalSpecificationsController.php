<?php

namespace App\Http\Controllers;

use App\Models\TechnicalSpecifications;
use App\Services\TechnicalSpecsService;
use App\Services\AssetService;
use Illuminate\Http\Request;

class TechnicalSpecificationsController extends Controller
{

    protected $technicalSpecsService;
    protected $assetService;

    /**
     * Constructs a new instance of the class.
     *
     * @param TechnicalSpecsService $technicalSpecsService The technical specifications service.
     * @param AssetService $assetService The asset service.
     */
    public function __construct(TechnicalSpecsService $technicalSpecsService, AssetService $assetService)
    {
        $this->technicalSpecsService = $technicalSpecsService;
        $this->assetService = $assetService;
    }

    /**
     * Display a listing of technical specifications.
     */
    public function index()
    {
        $technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $hardwareStandards = $this->assetService->showHardwareStandard();
        
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
     * @param $technical_spec - technical specification object
     */
    public function update(Request $request, TechnicalSpecifications $technical_spec)
    {
        $this->technicalSpecsService->updateTechnicalSpec($request, $technical_spec);

        return response()->json(['message' => 'Type has been updated successfully!']);
    }

    /**
     * Remove the specified technical specification from storage.
     * 
     * @param $technical_spec - technical specification object.
     */
    public function destroy(TechnicalSpecifications $technical_spec)
    {
        $this->technicalSpecsService->deleteTechnicalSpec($technical_spec);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
}
