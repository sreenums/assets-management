<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;

class EditAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $assetId = request()->route('asset')->id;   // Assuming your route parameter for asset ID is 'asset_id'

        $rules = [
            'assetType' => 'required',
            'hardwareStandard' => 'required',
            'technicalSpec' => 'required',
            'assetLocation' => 'required',
            'assetTag' => 'required|unique:assets,asset_tag', // Check if unique
            'serialNo' => [
                'required',
                Rule::unique('assets', 'serial_no')->ignore($assetId), // Ignore the current asset ID
            ],
            'assetTag' => [
                'required',
                Rule::unique('assets', 'asset_tag')->ignore($assetId), // Ignore the current asset ID
            ],
            'purchasingOrder' => 'required',
            'assetStatus' => 'required',
        ];
    
        if (request()->has('serialNo')) {
            $existingSerialNo = Asset::findOrFail($assetId)->serialNo;
            $requestSerialNo = request()->input('serialNo');
            
            if ($existingSerialNo == $requestSerialNo) {
                $rules['serialNo'] = 'required'; // Skip unique validation
            }
        }

        if (request()->has('assetTag')) {
            $existingAssetTag = Asset::findOrFail($assetId)->assetTag;
            $requestAssetTag = request()->input('assetTag');
            
            if ($existingAssetTag == $requestAssetTag) {
                $rules['assetTag'] = 'required'; // Skip unique validation
            }
        }
    
        return $rules;
    }
}
