<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class SmsRequest extends FormRequest
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
            "message" => "required",
            "recipient" => "required|numeric",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);
        if($exception->validator->errors()->has("message"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("message")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("recipient"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("recipient")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
