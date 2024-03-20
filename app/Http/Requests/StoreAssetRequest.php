<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
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
        return [
            'assetType' => 'required',
            'hardwareStandard' => 'required',
            'technicalSpec' => 'required',
            'assetLocation' => 'required',
            'assetTag' => 'required|unique:assets,asset_tag',       //chk if unique
            'serialNo' => 'required|unique:assets,serial_no',       //chk if unique
            'purchasingOrder' => 'required',
            'assetStatus' => 'required',
        ];
    }
}
