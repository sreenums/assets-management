<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditAssetRequest;
use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Services\AssetParameterService;
use App\Services\TechnicalSpecsService;
use App\Services\AssetService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        
        // if ($request->ajax()) {

        //     $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();
    
        //     // Total records before filtering
        //     $totalRecords = $assets->count();
    
        //     $assets = $this->assetService->filterAsset($request, $assets);
    
        //     // Total records after filtering
        //     $filteredRecords = $assets->count();
        //     $assets = $assets->skip($request->input('start'))->take($request->input('length'));     //->get() removed


        //     // Apply pagination
        //     // $start = $request->input('start', 0);
        //     // $length = $request->input('length', 10); // Default page length
        //     // $assets = $assets->slice($start)->take($length);

        //     // /**
        //     //  * Format for datatable
        //     //  * 
        //     //  */
        //     $formattedData = $this->assetService->formatDataTable($assets);
    
        //     return response()->json([ 
        //         'data' => $formattedData,
        //         'recordsTotal' => $totalRecords,
        //         'recordsFiltered' => $filteredRecords,
        //     ]);

        // }
        $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();

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
        $asset = $this->assetService->getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($id);
        $assetTypes = $this->assetParameterService->showAssetTypes();
        $hardwareStandards = $this->assetParameterService->showHardwareStandard();
        $technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $assetLocations = $this->assetParameterService->showLocations();
        $users = $this->assetParameterService->showUsers();

        return view('assets.asset-edit', compact('asset','assetTypes','assetLocations','hardwareStandards','technicalSpecs','users'));
        //return view('assets-edit', compact('post','users','categories','selectedCategoryIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditAssetRequest $request, Asset $asset)
    {
        $this->assetService->updateAsset($request, $asset);

        return back()->withSuccess('Asset has been updated successfully!');
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


    public function assetsList(Request $request)
    {
        // Log the incoming request
        //Log::info('Incoming request:', ['request' => $request->all()]);
        
        if ($request->ajax()) {    // Log the incoming AJAX request
            Log::info('Incoming AJAX request:', ['request' => $request->all()]);
        
            // Get assets list with required attributes
            $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();
        
            // Total records before filtering
            $totalRecords = $assets->count();
        
            // Filter assets based on request parameters
            $assets = $this->assetService->filterAsset($request, $assets);
        
            // Total records after filtering
            $filteredRecords = $assets->count();
        
            // Pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10); // Default page length
            $assets = $assets->skip($start)->take($length)->get();
        
            // Format for datatable
            $formattedData = $this->assetService->formatDataTable($assets);
        
            // Log the outgoing response
            Log::info('Outgoing AJAX response:', [
                'data' => $formattedData,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ]);
        
            return response()->json([ 
                'data' => $formattedData,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ]);
        }
    }
    
}
