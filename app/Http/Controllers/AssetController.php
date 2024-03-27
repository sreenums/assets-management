<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditAssetRequest;
use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Models\HardwareStandard;
use App\Models\Location;
use App\Models\TechnicalSpecifications;
use App\Models\Type;
use App\Models\User;
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
        $assets = $this->assetService->getAssetsListWithTypeHardwareStandardTechnicalSpecStatusAndLocation();
        $assetTypes = Type::all();
        $hardwareStandards = HardwareStandard::all();
        $technicalSpecs = TechnicalSpecifications::all();

        return view('home',compact('assets','assetTypes','hardwareStandards','technicalSpecs'));
    }

    /**
     * Show the form for adding an asset.
     */
    public function create()
    {
        $assetTypes = Type::all();
        $assetLocations = Location::all();
        $users = User::all();

        return view('assets.asset-add', compact('assetTypes','assetLocations','users'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreAssetRequest $request validated form request
     */
    public function store(StoreAssetRequest $request)
    {
        $this->assetService->createAsset($request);

        return back()->withSuccess('Asset added successfully!');
    }

    /**
     * Display the specified resource.
     * 
     * @param $asset Asset object
     */
    public function show(Asset $asset)
    {
        $asset = $this->assetService->loadAsset($asset);

        return view('assets.asset-view', compact('asset')); //, compact('asset','assetStatusText')
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param $id Asset Id
     */
    public function edit(string $id)
    {
        $asset = $this->assetService->getAssetWithTypeHardwareStandardTechnicalSpecStatusAndLocation($id);
        $assetTypes = Type::all();
        $hardwareStandards = HardwareStandard::all();
        $technicalSpecs = $this->technicalSpecsService->showTechnicalSpecs();
        $assetLocations = $this->assetParameterService->getDynamicLocation($asset->status);
        $users = User::select('id', 'name')->get();

        return view('assets.asset-edit', compact('asset','assetTypes','assetLocations','hardwareStandards','technicalSpecs','users'));
    }

    /**
     * Update the specified asset in storage.
     * 
     * @param EditAssetRequest $request validated form request
     * @param $asset Asset object
     */
    public function update(EditAssetRequest $request, Asset $asset)
    {
        $this->assetService->updateAsset($request, $asset);

        return back()->withSuccess('Asset has been updated successfully!');
    }

    /**
     * Remove the specified asset from storage.
     * 
     * @param $asset Asset object
     */
    public function destroy(Asset $asset)
    {
        $this->assetService->deleteAsset($asset);

        return response()->json(['success' => 'Post Deleted Successfully!']);
    }

    /**
     * For updating asset status.
     * 
     * @param $request ajax request
     * @param $id Asset Id
     */
    public function updateStatus(Request $request, $id)
    {
        $this->assetService->updateStatus($request, $id);

        return response()->json(['message' => 'Status has been updated successfully!']);
    }

    /**
     * Get list hardware standards with asset type.
     * 
     * @param $request ajax request data
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
     * Get list of technical specs with hardware standard.
     * 
     * @param $request ajax request data
     */
    public function getTechnicalSpecsWithHardwareStandard(Request $request)
    {
        $subTechnicalSpecs = $this->assetService->getTechnicalSpecsWithHardwareStandard($request);

        return response()->json([
            'status' => 'success',
            'subTechnicalSpecs' => $subTechnicalSpecs,
        ]);
    }

    /**
     * Get list of locations
     */
    public function getLocations()
    {
        $locations = $this->assetService->getLocations();
        return response()->json([
            'status' => 'success',
            'locations' => $locations,
        ]);
    }

    /**
     * Get list of assets for data table using ajax
     * 
     * @param $request ajax request
     */
    public function assetsList(Request $request)
    {
        if ($request->ajax()) {  
        
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
        
            return response()->json([ 
                'data' => $formattedData,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ]);
        }
    }
    
}
