<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Validation\Rule;

class CsvUploadService
{
    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    /**
     * Uploads a CSV file, processes the data, and stores it in the database.
     *
     * @param Request $request The HTTP request containing the file to be uploaded
     * @return \Illuminate\Http\JsonResponse Response indicating the success or failure of the upload process
     */
    public function uploadCsv($request)
    {
        if ($request->hasFile('importCsv')) {
            $path = $request->file('importCsv')->getRealPath();

            // Open the CSV file for reading
            $file = fopen($path, 'r');
            $header = fgetcsv($file);
            $rowCount = 0;

            while (($row = fgetcsv($file)) !== false) {

                $rowCount++;
                // Map the CSV columns to database columns
                $rowdata = array_combine($header, $row);

                $validator = $this->uploadFileValidator($rowdata);

                if ($validator->fails()) {
                    // Validation failed for the row data
                    $errors = $validator->errors()->all();
                    return response()->json(['error' => 'Validation Error', 'errorMessage' => $errors, 'row' => $rowCount]);
                }

                $data = $this->uploadData($row);

                $brandNew = config('custom.status.brandNew');

                if ($data['status'] != $brandNew) {
                    return response()->json(['error' => 'Status Validation Error', 'errorMessage' => 'Asset should be brand new.', 'row' => $rowCount]);
                }

                // Create or update records in the database
                $this->assetRepository->createAssetUpload($data);

            }

            fclose($file);
        }

        return response()->json(['message' => 'Asset File has been uploaded successfully!']);
    }

    /**
     * Uploads and validates a file.
     *
     * @param $rowdata - An array of row data
     */
    public function uploadFileValidator($rowdata)
    {
        return $validator = validator()->make($rowdata, [
            'asset_tag' => 'required|unique:assets,asset_tag',
            'serial_no' => 'required|unique:assets,serial_no',
            'type_id' => 'required',
            'hardware_standard_id' => 'required',
            'technical_specification_id' => 'required',
            'purchase_order' => 'nullable',
            'location_id' => 'required',
            'status' => [
                'required',
                Rule::in(['1']),
            ],
        ]);
    }

    /**
     * Formatting upload data
     * 
     * @param $row - An array of row data
     */
    public function uploadData($row)
    {
        return $data = [
            'asset_tag' => $row[0],
            'serial_no' => $row[1],
            'type_id' => $row[2],
            'hardware_standard_id' => $row[3],
            'technical_specification_id' => $row[4],
            'purchase_order' => $row[5],
            'location_id' => $row[6],
            'status' => $row[7],
        ];
    }
}