<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            "telephone" => Rule::unique('users','telephone'),
            "salon" => "required",
            "adresse" => "required",
        ];
    }

    public function messages()
    {
        return [
            "telephone.unique" => "Numéro de téléphone déjà utilisé",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        if($exception->validator->errors()->has("telephone"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("telephone")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("salon"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("salon")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("adresse"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("adresse")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
