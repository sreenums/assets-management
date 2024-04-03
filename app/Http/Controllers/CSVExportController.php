<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class CSVExportController extends Controller
{
    public function exportCsv(Request $request)
    {
        //dd($request->all());
        $data = Asset::all(); // Fetch your data

        $csvFileName = 'AssetData.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array('Asset Tag', 'Serial Number', 'Asset Type', 'Hardware Standard', 'Technical Specification','Purchase Order', 'User', 'Location','Status')); // Add column headers

            foreach ($data as $row) {
                fputcsv($file, array($row->asset_tag, $row->serial_no, $row->type_id, $row->hardware_standard_id, $row->technical_specification_id, $row->purchase_order, $row->user_id, $row->location_id, $row->status)); // Add data rows
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
