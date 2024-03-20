<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\AssetParameterService;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    protected $assetParameterService;

    public function __construct(AssetParameterService $assetParameterService)
    {
        $this->assetParameterService = $assetParameterService;
    }

    /**
     * Display a listing of Locations
     * 
     */
    public function index()
    {
        $locations = $this->assetParameterService->showLocations();
        
        return view('location-home', compact('locations'));
    }

    /**
     * Store a newly created location.
     * 
     * @param  $request form request data
     */
    public function store(Request $request)
    {
        $type = $this->assetParameterService->addLocation($request);
        
        return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetLocation, 'id' => $type->id ]);
    }

    /**
     * Update the specified location.
     * 
     * @param $request form request data
     * @param $location - Location object
     */
    public function update(Request $request, Location $location)
    {
        $this->assetParameterService->updateLocation($request, $location);

        return response()->json(['message' => 'Location has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param $location - Location object
     */
    public function destroy(Location $location)
    {
        $this->assetParameterService->deleteLocation($location);

        return response()->json(['success' => 'Location Deleted Successfully!']);
    }
}
