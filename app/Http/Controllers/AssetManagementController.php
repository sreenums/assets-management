<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use Illuminate\Http\Request;
use App\Services\AssetService;
use App\Services\TechnicalSpecsService;
use App\Services\AssetManagementService;

class AssetManagementController extends Controller
{

    protected $assetService;
    protected $technicalSpecsService;
    protected $assetManagementService;

    public function __construct(AssetService $assetService, TechnicalSpecsService $technicalSpecsService, AssetManagementService $assetManagementService)
    {
        $this->assetService = $assetService;
        $this->technicalSpecsService = $technicalSpecsService;
        $this->assetManagementService = $assetManagementService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the form for adding an asset.
     */
    public function create()
    {
        $assetTypes = $this->assetService->showAssetTypes();
        //$hardwareStandards = $this->assetService->showHardwareStandard();
        //$technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $assetLocations = $this->assetService->showLocations();
        $users = $this->assetService->showUsers();

        return view('assets.asset-add', compact('assetTypes','assetLocations','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $this->assetManagementService->createAsset($request);

        return back()->withSuccess('Asset registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get list hardware standards with asset type.
     * 
     * @param $request form request data
     */
    public function getHardwareStandardWithType(Request $request)
    {
        //$subHardwareStandards = $this->model->where('type_id', $request->assetType)->get();
        $subHardwareStandards = $this->assetManagementService->getHardwareStandardWithType($request);

        return response()->json([
            'status' => 'success',
            'subHardwareStandards' => $subHardwareStandards,
        ]);
    }

    /**
     * Get list technical specs with hardware standard.
     * 
     * @param $request form request data
     */
    public function getTechnicalSpecsWithHardwareStandard(Request $request)
    {
        $subTechnicalSpecs = $this->assetManagementService->getTechnicalSpecsWithHardwareStandard($request);

        return response()->json([
            'status' => 'success',
            'subTechnicalSpecs' => $subTechnicalSpecs,
        ]);
    }
    
}
