<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class AssetParameterController extends Controller
{

    public function index()
    {
        // $types = Type::getList();
        // return view('type-home', compact('types'));
    }

    public function saveTypeData(Request $request)
    {
        // // Validate incoming data
        // $validatedData = $request->validate([
        //     'assetType' => 'required|string|max:255',
        // ]);

        // $data = ['type' => $request->assetType];
        // // Save data to your database
        // Type::create($data);

        // $rowCount = Type::count();;
        // //return view('type-home', compact('types'));
        // return response()->json(['message' => 'Data has been saved!', 'type' => $request->assetType, 'count' => $rowCount ]);
    }
}
