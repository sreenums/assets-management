<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Support\Facades\Log;

class LocationRepository
{
    protected $model;

    public function __construct(Location $location)
    {
        $this->model = $location;
    }

    public function showLocations()
    {
        return $this->model->get();
    }

    public function addLocation($data)
    {
        // Create the location
        return $this->model->create($data);
    }

    public function updateLocation($location, $locationData)
    {
        return $location->update($locationData);
    }

    public function deleteLocation($location)
    {
        return $location->delete();
    }

    public function getLocations()
    {
       return $this->model->get();
    }

}
