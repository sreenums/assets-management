<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditAssetRequest;
use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Models\HardwareStandard;
use App\Models\Location;
use App\Models\Status;
use App\Models\TechnicalSpecifications;
use App\Models\Type;
use App\Models\User;
use App\Services\AssetFilterService;
use App\Services\AssetHistoryService;
use Illuminate\Http\Request;
use App\Services\AssetService;
use App\Services\StatusService;

class AssetController extends Controller
{

    protected $assetFilterService;
    protected $assetService;
    protected $statusService;
    protected $assetHistoryService;

    public function __construct(AssetFilterService $assetFilterService, AssetService $assetService, StatusService $statusService, AssetHistoryService $assetHistoryService)
    {
        $this->assetFilterService = $assetFilterService;
        $this->assetService = $assetService;
        $this->statusService = $statusService;
        $this->assetHistoryService = $assetHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $assets = $this->assetService->getAssetsList();
        $assetTypes = Type::select('id', 'type')->get();
        $hardwareStandards = HardwareStandard::select('id', 'description')->get();
        $technicalSpecs = TechnicalSpecifications::select('id', 'description')->get();

        return view('home',compact('assets','assetTypes','hardwareStandards','technicalSpecs'));
    }

    /**
     * Show the form for adding an asset.
     */
    public function create()
    {
        $assetTypes = Type::select('id', 'type')->get();
        $assetLocations = Location::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();

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
     * Display the specified asset.
     * 
     * @param $asset Asset object
     */
    public function show(Asset $asset)
    {
        $asset = $this->assetService->loadAsset($asset);
        $statuses = $this->statusService->getAssetStatuses();
        $histories = $this->assetHistoryService->getAssetHistory($asset->id);

        return view('assets.asset-view', compact('asset','statuses','histories')); //, compact('asset','assetStatusText')
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param $id Asset Id
     */
    public function edit(string $id)
    {
        $asset = $this->assetService->getAsset($id);
        $assetTypes = Type::select('id', 'type')->get();
        $hardwareStandards = HardwareStandard::select('id', 'description')->get();
        $technicalSpecs = TechnicalSpecifications::select('id', 'description')->get();
        $assetLocations = $this->assetFilterService->getDynamicLocation($asset->status);
        $users = User::select('id', 'name')->get();
        $statuses = Status::select('id', 'name')->get();

        return view('assets.asset-edit', compact('asset','assetTypes','assetLocations','hardwareStandards','technicalSpecs','users','statuses'));
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
        $this->statusService->updateStatus($request, $id);

        return response()->json(['message' => 'Status has been updated successfully!']);
    }

    /**
     * Get list hardware standards with asset type.
     * 
     * @param $request ajax request data
     */
    public function getHardwareStandardWithType(Request $request)
    {
        $subHardwareStandards = $this->assetFilterService->getHardwareStandardWithType($request);

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
        $subTechnicalSpecs = $this->assetFilterService->getTechnicalSpecsWithHardwareStandard($request);

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
        $locations = $this->assetFilterService->getLocations();
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
            $assets = $this->assetService->getAssetsList();
        
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
