<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class PanierRequest extends FormRequest
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
            "date" => "nullable|date",
            "items" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        if($exception->validator->errors()->has("date"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("date")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("items"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("items")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
