<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Services\AssetTypeService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetTypeController extends Controller
{
    protected $assetTypeService;

    public function __construct(assetTypeService $assetTypeService)
    {
        $this->assetTypeService = $assetTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assetTypes = $this->assetTypeService->showAssetTypes();
        
        return view('type-home', compact('assetTypes'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param $request form request
     */
    public function store(Request $request)
    {
        $type = $this->assetTypeService->addType($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetType, 'id' => $type->id ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param $request form request
     * @param $assets_type Type object
     */
    public function update(Request $request, Type $assets_type)
    {
        $this->assetTypeService->updateType($request, $assets_type);

        return response()->json(['message' => 'Asset type has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param $assets_type Type object
     */
    public function destroy(Type $assets_type)
    {
        try {
            $this->assetTypeService->deleteType($assets_type);

            return response()->json(['success' => 'Asset type Deleted Successfully!']);
            
        } catch (QueryException $e) {
            
            $integrityConstraintViolation = config('custom.sqlErrorCodes.integrityConstraintViolation');

            // Check if the exception is due to a Integrity constraint violation
            if ($e->getCode() === $integrityConstraintViolation) {
                return response()->json(['error' => 'Hardware standard is added for the Asset type, Please delete it first!!']);
            }else{
                return response()->json(['error' => 'An unexpected error occurred!!']);
            }
        }
    }
    
}
