<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use Illuminate\Http\Request;
use App\Services\AssetParameterService;
use App\Services\TechnicalSpecsService;
use App\Services\AssetService;

class AssetController extends Controller
{

    protected $assetParameterService;
    protected $technicalSpecsService;
    protected $assetService;

    public function __construct(AssetParameterService $assetParameterService, TechnicalSpecsService $technicalSpecsService, AssetService $assetService)
    {
        $this->assetParameterService = $assetParameterService;
        $this->technicalSpecsService = $technicalSpecsService;
        $this->assetService = $assetService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecAndStatus();
        
        if ($request->ajax()) {
            
            $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecAndStatus();

            dd($assets);
            // Total records before filtering
            // $totalRecords = $assets->count();
            
            // $posts = $this->assetService->FilterPost($request, $posts);

            // // Total records after filtering
            // $filteredRecords = $posts->count();
            // $posts = $posts->skip($request->input('start'))->take($request->input('length'))->get();

            // /**
            //  * Format for datatable
            //  * 
            //  */
            // $formattedData = $this->assetService->formatDataTable($posts);

            // // Return JSON response with data and counts
            // return response()->json([
            //     'data' => $formattedData,
            //     'recordsTotal' => $totalRecords,
            //     'recordsFiltered' => $filteredRecords,
            // ]);
        }

        return view('home',compact('assets'));
    }

    /**
     * Show the form for adding an asset.
     */
    public function create()
    {
        $assetTypes = $this->assetParameterService->showAssetTypes();
        //$hardwareStandards = $this->assetParameterService->showHardwareStandard();
        //$technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $assetLocations = $this->assetParameterService->showLocations();
        $users = $this->assetParameterService->showUsers();

        return view('assets.asset-add', compact('assetTypes','assetLocations','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $this->assetService->createAsset($request);

        return back()->withSuccess('Asset added successfully!');
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
        $subHardwareStandards = $this->assetService->getHardwareStandardWithType($request);

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
        $subTechnicalSpecs = $this->assetService->getTechnicalSpecsWithHardwareStandard($request);

        return response()->json([
            'status' => 'success',
            'subTechnicalSpecs' => $subTechnicalSpecs,
        ]);
    }
    
}
