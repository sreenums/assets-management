<?php

namespace App\Services;

use App\Models\HardwareStandard;
use App\Models\Location;
use App\Repositories\LocationRepository;

class LocationService
{
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Get list of Locations
     */
    public function showLocations()
    {
        return $this->locationRepository->getLocations();
    }

    /**
     * Add Location to storage
     * 
     * @param $request - form request data
     */
    public function addLocation($request)
    {
        $validatedData = $request->validate([
            'assetLocation' => 'required|string|max:255',
        ]);

        $data = ['name' => $request->assetLocation, 'type' => 'home'];

        return $this->locationRepository->addLocation($data);
    }

    /**
     * Delete Location from storage
     * 
     * @param $location - Location object
     */
    public function deleteLocation($location)
    {
        return $this->locationRepository->deleteLocation($location);
    }

    /**
     * Update Location to storage
     * 
     * @param $request - form request data
     * @param $location - Location object
     */
    public function updateLocation($request, $location)
    {
        $validatedData = $request->validate([
            'editLocation' => 'required|string|max:255',
        ]);

        $locationData = [
            'name' => $request->editLocation,
        ];

        return $type = $this->locationRepository->updateLocation($location, $locationData);
    }
    
}