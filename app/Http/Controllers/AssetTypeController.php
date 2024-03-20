<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Services\AssetParameterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetTypeController extends Controller
{
    protected $assetParameterService;

    public function __construct(AssetParameterService $assetParameterService)
    {
        $this->assetParameterService = $assetParameterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assetTypes = $this->assetParameterService->showAssetTypes();
        
        return view('type-home', compact('assetTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $this->assetParameterService->addType($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetType, 'id' => $type->id ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $assets_type)
    {
        $this->assetParameterService->updateType($request, $assets_type);

        return response()->json(['message' => 'Type has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $assets_type)
    {
        // Call the asset service to delete the type
        $this->assetParameterService->deleteType($assets_type);

        return response()->json(['success' => 'Type Deleted Successfully!']);
    }
    
}
