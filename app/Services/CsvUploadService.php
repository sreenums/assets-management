<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CsvUploadService
{
    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    /**
     * Validate and show the file data.
     *
     * @param Request $request The HTTP request containing the file to be uploaded
     * @return FileData The formatted file data
     */
    public function uploadCsv($request)
    {
        if ($request->hasFile('importCsv')) {

            $tableData = $this->fileValidateAndShow($request);

            return response()->json($tableData);
        }
    }

    /**
     * Validates the input file.
     *
     * @param $rowdata - An array of validated data
     */
    public function uploadFileValidator($rowdata)
    {
        $brandNew = config('custom.status.brandNew');

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
                Rule::in(["$brandNew"]),
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
        $currentTimestamp = Carbon::now();

        return $data = [
            'asset_tag' => $row['asset_tag'],
            'serial_no' => $row['serial_no'],
            'type_id' => $row['type_id'],
            'hardware_standard_id' => $row['hardware_standard_id'],
            'technical_specification_id' => $row['technical_specification_id'],
            'purchase_order' => $row['purchase_order'],
            'location_id' => $row['location_id'],
            'status' => $row['status'],
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,

        ];

    }

    /**
     * Save the uploaded data to database.
     *
     * @param datatype $request The request containing the uploaded file
     * @return Some_Return_Value The JSON response indicating successful upload
     */
    public function saveUploadData($request)
    {
        if ($request->hasFile('importCsv')) {

            $dataCollection = $this->getCsvDataCollection($request);
            $this->assetRepository->insertAssetUpload($dataCollection);
    
            return response()->json(['message' => 'Asset File has been uploaded successfully!']);
        }
    }

    /**
     * Get the CSV data collection for save
     * 
     * @param $request - The request containing the uploaded file
     * @return $data - formatted upload data for save
     */
    public function getCsvDataCollection($request)
    {
        $path = $request->file('importCsv')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
        $rowCount = 0;

        //$errorRows - To skip error and removed rows
        $errorRows = $request->input('errorRows');
        $errorRows = json_decode($errorRows);
        $data = [];

        while (($row = fgetcsv($file)) !== false) {
            $rowCount++;
            if (in_array($rowCount, $errorRows)) {
                continue;
            }
            $rowdata = array_combine($header, $row);

            $data[] = $this->uploadData($rowdata);
        }

        fclose($file);

        return $data;
    }

    /**
     * Validate File and Show
     * 
     * @param $request - The request containing the uploaded file
     */
    public function fileValidateAndShow($request)
    {
        $path = $request->file('importCsv')->getRealPath();

        // Open the CSV file for reading
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
        $rowCount = 0;
        $successfulRecords = array();
        $failedRecords = array();

        $expectedFileHeaders = config('custom.expectedFileHeaders');
        $missingHeaders = array_diff($expectedFileHeaders, $header);

        // If there is header mismatch, return error message
        if (!empty($missingHeaders)) {

            return $this->headerMissing($missingHeaders);
        }
        if (count($expectedFileHeaders) !== count($header)) {

            return $this->fileHeaderMismatch();
        }

        while (($row = fgetcsv($file)) !== false) {

            $rowCount++;
            // Map the CSV columns to database columns
            $rowdata = array_combine($header, $row);

            $validator = $this->uploadFileValidator($rowdata);

            if ($validator->fails()) {
                // Validation failed for the row data
                $errors = $validator->errors()->all();
                $failedRecords[] = ['data' => $rowdata, 'errors' => $errors, 'row' => $rowCount];

            }else{

                $brandNew = config('custom.status.brandNew');

                if ($rowdata['status'] != $brandNew) {
                    $failedRecords[] = ['data' => $rowdata, 'errors' => ['Status Validation Error: Asset should be brand new.'], 'row' => $rowCount];
                }else{
                $successfulRecords[] = ['data' => $rowdata, 'row' => $rowCount];
                }
            }

        }

        fclose($file);

        return $this->showValidatedData($header, $failedRecords, $successfulRecords);

    }

    /**
     * File Header count mismatch error
     * 
     * @return array of error messages
     */
    public function fileHeaderMismatch()
    {
        return [
            'fileTypeError' => 'Invalid File Format!',
            'errorMessage' => 'File Headers do not match the expected headers!',
        ];
    }

    /**
     * Required header missing
     * 
     * @param $missingHeaders - An array of missing headers
     * @return array of error messages
     */
    public function headerMissing($missingHeaders)
    {
        $missingHeadersString = implode(', ', $missingHeaders);
            
        return [
            'fileTypeError' => 'Invalid File Format! The following headers are missing from the file: ',
            'errorMessage' => $missingHeadersString,
        ];
    }

    /**
     * Show validated data before file save
     * 
     * @param $header - The CSV file header
     * @param $failedRecords - An array of failed records
     * @param $successfulRecords - An array of successful records
     */
    public function showValidatedData($header, $failedRecords, $successfulRecords)
    {
        // Prepare table data
        return $tableData = [
                'failedRecords' => [
                    'header' => $header,
                    'rows' => $failedRecords
                ],
                'successfulRecords' => [
                    'header' => $header,
                    'rows' => $successfulRecords
                ]
            ];
    }

}