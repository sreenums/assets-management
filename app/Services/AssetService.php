<?php

namespace App\Services;

use App\Models\Type;
use App\Repositories\AssetTypeRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;

class AssetService
{
    protected $assetTypeRepository;
    protected $hardwareStandardRepository;
    protected $locationRepository;
    protected $userRepository;

    public function __construct(AssetTypeRepository $assetTypeRepository, HardwareStandardRepository $hardwareStandardRepository, LocationRepository $locationRepository, UserRepository $userRepository)
    {
        $this->assetTypeRepository = $assetTypeRepository;
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->locationRepository = $locationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Show Assets types
     * 
     */
    public function showAssetTypes()
    {
       return $this->assetTypeRepository->showAssetTypes();
    }
    
    /**
     * Add Assets type to the storage
     * 
     * @param $request - form request data
     */
    public function addType($request)
    {
        $validatedData = $request->validate([
            'assetType' => 'required|string|max:255',
        ]);

        $data = ['type' => $request->assetType];

        return $this->assetTypeRepository->addType($data);
    }

    /**
     * Update Asset type
     * 
     * @param $request - form request data
     * @param $type - asset type object
     */
    public function updateType($request, $type)
    {
        $typeData = [
            'type' => $request->editAssetType,
        ];

        return $type = $this->assetTypeRepository->updateType($type, $typeData);
    }

    /**
     * Delete Asset type
     * 
     * @param $type - asset type object
     */
    public function deleteType($type)
    {
        return $this->assetTypeRepository->deleteType($type);
    }


    /**
     * Hardware Standards Services 
     * 
     */

     /**
      * Get list of Hardware Standards
      * 
      */
     public function showHardwareStandard()
     {
        return $this->hardwareStandardRepository->showHardwareStandard();
     }

     /**
      * Add Hardware Standard to storage
      *
      * @param $request - form request data
      */
     public function addHardwareStandard($request)
     {
        $validatedData = $request->validate([
            'assetHardwareStandard' => 'required|string|max:255',
            'assetType' => 'required|string|max:255',
        ]);

        $data = ['description' => $request->assetHardwareStandard
        , 'type_id' => $request->assetType];

        return $this->hardwareStandardRepository->addHardwareStandard($data);
     }

     /**
      * Delete Hardware Standard from storage
      *
      * @param $hardwareStandard - hardware standard object
      */
     public function deleteHardwareStandard($hardwareStandard)
     {
         return $this->hardwareStandardRepository->deleteHardwareStandard($hardwareStandard);
     }

     /**
      * Update Hardware Standard to storage
      *
      * @param $request - form request data
      * @param $hardwareStandard - hardware standard object
      */
     public function updateHardwareStandard($request, $hardwareStandard)
     {
         $hardwareStandardData = [
             'description' => $request->editHardwareStandard,
             'type_id' => $request->assetTypeEdit,
         ];
 
         return $type = $this->hardwareStandardRepository->updateHardwareStandard($hardwareStandard, $hardwareStandardData);
     }


    /**
     * Location services
    *
    */

    /**
     * Get list of Locations
     */
    public function showLocations()
    {
        return $this->locationRepository->showLocations();
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

        $data = ['name' => $request->assetLocation];

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


    /**
     * User services
     */

    /**
     * Show list of users
     * 
     */
    public function showUsers()
    {
        return $this->userRepository->showUsers();
    }

    /**
     * Add User to storage
     * 
     * @param $request - form request data
     */
    public function addUser($request)
    {
        $validatedData = $request->validate([
            'assetUser' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $data = ['name' => $request->assetUser, 'email' => $request->email];

        return $this->userRepository->addUser($data);
    }

    /**
     * Delete User from storage
     * 
     * @param $location - Location object
     */
    public function deleteUser($location)
    {
        return $this->userRepository->deleteUser($location);
    }

    /**
     * Update User to storage
     * 
     * @param $request - form request data
     * @param $user - User object
     */
    public function updateUser($request, $user)
    {
        $validatedData = $request->validate([
            'editUserName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255',
        ]);

        $userData = [
            'name' => $request->editUserName,
            'email' => $request->editEmail,
        ];

        return $type = $this->userRepository->updateUser($user, $userData);
    }


}