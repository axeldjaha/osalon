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
            "total" => "required",
            "date" => "nullable|date",
            "article_paniers" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        if($exception->validator->errors()->has("total"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("total")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("date"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("date")[0],
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
    }
}
