<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ArticleRequest extends FormRequest
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
            "libelle" => "required",
            "prix" => "required",
            "stock" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);
        if($exception->validator->errors()->has("libelle"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("libelle")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("prix"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("prix")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("stock"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("stock")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
