<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value_januari' => 'required',
            'value_febuari' => 'required',
            'value_maret' => 'required',
            'value_april' => 'required',
            'value_mei' => 'required',
            'value_juni' => 'required',
            'value_juli' => 'required',
            'value_agustus' => 'required',
            'value_september' => 'required',
            'value_oktober' => 'required',
            'value_november' => 'required',
            'value_desember' => 'required',
            'value_year' => 'required',
            'user_id' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
            'valid' => false
        ], 422));
    }
}
