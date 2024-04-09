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
     * Uploads a CSV file, processes the data, and stores it in the database.
     *
     * @param Request $request The HTTP request containing the file to be uploaded
     * @return \Illuminate\Http\JsonResponse Response indicating the success or failure of the upload process
     */
    public function uploadCsv($request)
    {
        if ($request->hasFile('importCsv')) {

            $tableData = $this->fileValidateAndShow($request);

            return response()->json($tableData);
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
        $currentTimestamp = Carbon::now();
        return $data = [
            'asset_tag' => $row[0],
            'serial_no' => $row[1],
            'type_id' => $row[2],
            'hardware_standard_id' => $row[3],
            'technical_specification_id' => $row[4],
            'purchase_order' => $row[5],
            'location_id' => $row[6],
            'status' => $row[7],
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
            $this->assetRepository->createAssetUpload($dataCollection);
    
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

            $data[] = $this->uploadData($row);
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

                    $data = $this->uploadData($row);

                    $brandNew = config('custom.status.brandNew');

                    if ($data['status'] != $brandNew) {
                        $failedRecords[] = ['data' => $rowdata, 'errors' => ['Status Validation Error: Asset should be brand new.'], 'row' => $rowCount];
                    }else{
                    $successfulRecords[] = ['data' => $rowdata, 'row' => $rowCount];
                    }
                }

            }

            fclose($file);

            // Prepare table data
            $tableData = [
                'failedRecords' => [
                    'header' => $header,
                    'rows' => $failedRecords
                ],
                'successfulRecords' => [
                    'header' => $header,
                    'rows' => $successfulRecords
                ]
            ];

            return $tableData;
    }


}