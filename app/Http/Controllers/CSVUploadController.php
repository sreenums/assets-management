<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\CsvUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CSVUploadController extends Controller
{
    protected $csvUploadService;

    public function __construct(CsvUploadService $csvUploadService)
    {
        $this->csvUploadService = $csvUploadService;
    }

    public function index(Request $request)
    {
        return view('assets.bulk-add');
    }

    /**
     * Uploads a CSV asset based on the given request.
     *
     * @param Request $request The request containing the CSV file to upload
     */
    public function uploadAssetCsv(Request $request)
    {
        
        $validatorFileType = Validator::make($request->all(), [
            'importCsv' => 'required|mimes:csv,txt|max:10240',
        ]);

        // Check if validation fails
        if ($validatorFileType->fails()) {

            // Return validation errors
            return response()->json([
                'fileTypeError' => 'File Type Validation Error',
                'errorMessage' => $validatorFileType->errors()->all(),
            ]);
        }
        
        return $this->csvUploadService->uploadCsv($request);
        
    }

    public function saveUploadData(Request $request)
    {
        return $this->csvUploadService->saveUploadData($request);
    }

}
