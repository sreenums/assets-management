<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\AssetService;
use App\Services\CsvExportService;
use Illuminate\Http\Request;

class CSVExportController extends Controller
{

    protected $assetService;
    protected $csvExportService;

    public function __construct(AssetService $assetService, CsvExportService $csvExportService)
    {
        $this->assetService = $assetService;
        $this->csvExportService = $csvExportService;
    }

    /**
     * Export CSV File
     * 
     * @param Request $request
     * @return generated CSV response
     */
    public function exportCsv(Request $request)
    {
        $assets = $this->assetService->getAssetsList();
        $assets = $this->assetService->filterAsset($request, $assets)->get();

        $dataFile = $this->csvExportService->csvGenerate($assets, $request);
        $headers = $this->csvExportService->getHeaders();

        return response()->stream($dataFile, 200, $headers);
    }

}
