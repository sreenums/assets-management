<?php

namespace App\Services;

use App\Models\Type;
use App\Repositories\AssetTypeRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use Dotenv\Exception\ValidationException;
use Dotenv\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class AssetParameterService
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

    // /**
    //  * Show Assets types
    //  * 
    //  */
    // public function showAssetTypes()
    // {
    //    return $this->assetTypeRepository->showAssetTypes();
    // }
    
    // /**
    //  * Add Assets type to the storage
    //  * 
    //  * @param $request - form request data
    //  */
    // public function addType($request)
    // {
    //     $validatedData = $request->validate([
    //         'assetType' => 'required|string|max:255',
    //     ]);

    //     $data = ['type' => $request->assetType];

    //     return $this->assetTypeRepository->addType($data);
    // }

    // /**
    //  * Update Asset type
    //  * 
    //  * @param $request - form request data
    //  * @param $type - asset type object
    //  */
    // public function updateType($request, $type)
    // {
    //     $typeData = [
    //         'type' => $request->editAssetType,
    //     ];

    //     return $type = $this->assetTypeRepository->updateType($type, $typeData);
    // }

    // /**
    //  * Delete Asset type
    //  * 
    //  * @param $type - asset type object
    //  */
    // public function deleteType($type)
    // {
    //     return $this->assetTypeRepository->deleteType($type);
    // }


/**
 * Hardware Standards Services 
 * 
 */

    //  /**
    //   * Get list of Hardware Standards
    //   * 
    //   */
    //  public function showHardwareStandard()
    //  {
    //     return $this->hardwareStandardRepository->showHardwareStandard();
    //  }

    //  /**
    //   * Add Hardware Standard to storage
    //   *
    //   * @param $request - form request data
    //   */
    //  public function addHardwareStandard($request)
    //  {
    //     $validatedData = $request->validate([
    //         'assetHardwareStandard' => 'required|string|max:255',
    //         'assetType' => 'required|string|max:255',
    //     ]);

    //     $data = ['description' => $request->assetHardwareStandard
    //     , 'type_id' => $request->assetType];

    //     return $this->hardwareStandardRepository->addHardwareStandard($data);
    //  }

    //  /**
    //   * Delete Hardware Standard from storage
    //   *
    //   * @param $hardwareStandard - hardware standard object
    //   */
    //  public function deleteHardwareStandard($hardwareStandard)
    //  {
    //      return $this->hardwareStandardRepository->deleteHardwareStandard($hardwareStandard);
    //  }

    //  /**
    //   * Update Hardware Standard to storage
    //   *
    //   * @param $request - form request data
    //   * @param $hardwareStandard - hardware standard object
    //   */
    //  public function updateHardwareStandard($request, $hardwareStandard)
    //  {
    //      $hardwareStandardData = [
    //          'description' => $request->editHardwareStandard,
    //          'type_id' => $request->assetTypeEdit,
    //      ];
 
    //      return $type = $this->hardwareStandardRepository->updateHardwareStandard($hardwareStandard, $hardwareStandardData);
    //  }


/**
 * Location services
 *
 */

    // /**
    //  * Get list of Locations
    //  */
    // public function showLocations()
    // {
    //     return $this->locationRepository->getLocations();
    // }

    // /**
    //  * Add Location to storage
    //  * 
    //  * @param $request - form request data
    //  */
    // public function addLocation($request)
    // {
    //     $validatedData = $request->validate([
    //         'assetLocation' => 'required|string|max:255',
    //     ]);

    //     $data = ['name' => $request->assetLocation, 'type' => 'home'];

    //     return $this->locationRepository->addLocation($data);
    // }

    // /**
    //  * Delete Location from storage
    //  * 
    //  * @param $location - Location object
    //  */
    // public function deleteLocation($location)
    // {
    //     return $this->locationRepository->deleteLocation($location);
    // }

    // /**
    //  * Update Location to storage
    //  * 
    //  * @param $request - form request data
    //  * @param $location - Location object
    //  */
    // public function updateLocation($request, $location)
    // {
    // $validatedData = $request->validate([
    //     'editLocation' => 'required|string|max:255',
    // ]);

    // $locationData = [
    //     'name' => $request->editLocation,
    // ];

    // return $type = $this->locationRepository->updateLocation($location, $locationData);
    // }


/**
 * User services
 */



    /**
     * Get dynamic list of locations based on status
     */
    public function getDynamicLocation($assetStatus)
    {   
        //User assigned
        if($assetStatus == '2'){
            return $this->userRepository->getUsers();
        }else{
            return $this->locationRepository->getLocations();
        }
    }

}