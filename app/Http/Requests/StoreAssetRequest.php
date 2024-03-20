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
            'user' => 'required',           //check if user/location exists
            'assetLocation' => 'required',  //check if user/location exists
            'assetTag' => 'required',       //chk if unique
            'serialNo' => 'required',       //chk
            'purchasingOrder' => 'required',
            'assetStatus' => 'required',
        ];
    }
}
