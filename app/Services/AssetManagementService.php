<?php

namespace App\Services;

use App\Repositories\AssetManagementRepository;
use App\Repositories\HardwareStandardRepository;
use App\Repositories\TechnicalSpecsRepository;

class AssetManagementService
{
    protected $assetManagementRepository;
    protected $hardwareStandardRepository;
    protected $technicalSpecsRepository;

    public function __construct(AssetManagementRepository $assetManagementRepository, HardwareStandardRepository $hardwareStandardRepository, TechnicalSpecsRepository $technicalSpecsRepository)
    {
        $this->assetManagementRepository = $assetManagementRepository;
        $this->hardwareStandardRepository = $hardwareStandardRepository;
        $this->technicalSpecsRepository = $technicalSpecsRepository;
    }

    /**
     * Get list of hardware standards for an asset type
     * 
     * @param $request - form request data(assetType)
     */
    public function getHardwareStandardWithType($request)
    {
        return $this->hardwareStandardRepository->getHardwareStandardWithType($request);
    }

    public function getTechnicalSpecsWithHardwareStandard($request)
    {
        return $this->technicalSpecsRepository->getTechnicalSpecsWithHardwareStandard($request);
    }

    public function createAsset($request)
    {
        $assetData = $this->getAssetCreateData($request);
        return $this->assetManagementRepository->createAsset($assetData);
    }

    /**
     * Generate the post data
     * 
     * @param $request Form data
     */
    public function getAssetCreateData($request)
    {   
        return [
            'title' => $request->title,
            'user_id' => $request->author,
            'content' => $request->content,
            'date_published' => $request->datePublished,
            'is_active' => $request->checkActive,
        ];
    }


}