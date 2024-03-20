<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\AssetService;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Display a listing of Locations
     * 
     */
    public function index()
    {
        $locations = $this->assetService->showLocations();
        
        return view('location-home', compact('locations'));
    }

    /**
     * Store a newly created location.
     * 
     * @param  $request form request data
     */
    public function store(Request $request)
    {
        $type = $this->assetService->addLocation($request);
        
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
        $this->assetService->updateLocation($request, $location);

        return response()->json(['message' => 'Location has been updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param $location - Location object
     */
    public function destroy(Location $location)
    {
        $this->assetService->deleteLocation($location);

        return response()->json(['success' => 'Location Deleted Successfully!']);
    }
}
