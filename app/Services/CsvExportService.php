<?php

namespace App\Services;

class CsvExportService
{

    /**
     * Get the headers for exporting data as a CSV file.
     *
     * @return array $headers An array containing the headers for CSV export
     */
    public function getHeaders()
    {
        $csvFileName = 'AssetData.csv';

        return $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
    }
    
    /**
     * Generate CSV File
     * 
     * @param $assets - list of filtered assets
     * @param $request - request data object
     */
    public function csvGenerate($assets, $request)
    {
        $period = $this->showPeriod($request);
        return $this->generateFile($assets, $period);
    }

    /**
     * To display period of filtering
     * 
     * @param $request - request data
     */
    public function showPeriod($request)
    {
        $period = '';
        if(isset($request->days) && $request->days > 0){
            $period = $request->days.' days ';
        }
        if(isset($request->months) && $request->months > 0){
            $period .= $request->months.' months ';
        }
        if(isset($request->years) && $request->years > 0){
            $period .= $request->years.' years ';
        }

        return $period;
    }

    /**
     * Create CSV File with data
     * 
     * @param $assets - list of filtered assets
     * @param $period - Period of filtering applyed
     */
    public function generateFile($assets, $period)
    {
        return function() use ($assets, $period) {
            $file = fopen('php://output', 'w');

            // Filtering period
            fputcsv($file, array('Period of Filtering:', $period));

            // Data headers
            fputcsv($file, array('Asset Tag', 'Serial Number', 'Asset Type', 'Hardware Standard', 'Technical Specification','Purchase Order', 'User', 'Location','Status'));

            foreach ($assets as $row) {
                $username = ''; $location = '';
                if($row->user){
                    $username = $row->user->name;
                }
                if($row->location){
                    $location = $row->location->name;
                }
                fputcsv($file, array($row->asset_tag, $row->serial_no, $row->type->type, $row->hardwareStandard->description, $row->technicalSpecification->description, $row->purchase_order, $username, $location, $row->status_text));
            }
            fclose($file);
        };
    }


}