<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class PaiementRequest extends FormRequest
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
            "engin" => "nullable",
            "articles" => "nullable",
            "laveurs" => "nullable",
            "immatriculation" => "nullable",
            "cout" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        if($exception->validator->errors()->has("engin"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("engin")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("articles"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("articles")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("laveurs"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("laveurs")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("cout"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("cout")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
