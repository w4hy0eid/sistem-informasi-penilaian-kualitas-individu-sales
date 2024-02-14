<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSalesRequest extends FormRequest
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
            'user_id' => 'required',
            'judul_project' => 'required',
            'nama_pelanggan' => 'required',
            'mitra' => 'required',
            'deal_dibulan' => 'required',
            'nilai_project' => 'required',
            'lama_kontrak' => 'required',
            'pembayaran_bulanan' => 'required',
            'type' => 'required'
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
