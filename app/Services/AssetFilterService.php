<?php

namespace App\Services;

use App\Repositories\HardwareStandardRepository;
use App\Repositories\LocationRepository;
use App\Repositories\TechnicalSpecsRepository;
use App\Repositories\UserRepository;

class AssetFilterService
{
    protected $hardwareStandardRepository;
    protected $technicalSpecsRepository;
    protected $userRepository;
    protected $locationRepository;

    public function __construct(HardwareStandardRepository $hardwareStandardRepository, TechnicalSpecsRepository $technicalSpecsRepository, UserRepository $userRepository, LocationRepository $locationRepository)
    {
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->technicalSpecsRepository = $technicalSpecsRepository;
        $this->userRepository = $userRepository;
        $this->locationRepository = $locationRepository;
    }

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

    /**
     * Get list of hardware standards for an asset type
     * 
     * @param $request - ajax request data(assetType)
     */
    public function getHardwareStandardWithType($request)
    {
        return $this->hardwareStandardRepository->getHardwareStandardWithType($request);
    }    

    /**
     * Get list of technical specs with hardware standard
     * 
     * @param $request - ajax request data
     */
    public function getTechnicalSpecsWithHardwareStandard($request)
    {
        return $this->technicalSpecsRepository->getTechnicalSpecsWithHardwareStandard($request);
    }

    /**
     * Get list of locations
     */
    public function getLocations()
    {
        return $this->locationRepository->getLocations();
    }

}