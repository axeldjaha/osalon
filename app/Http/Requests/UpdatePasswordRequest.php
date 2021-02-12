<?php

namespace App\Http\Requests;

use App\Rules\MatchPassword;
use App\Rules\MatchUserPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
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
            "password" => ['required', new MatchUserPassword()],
            "new_password" => "required|min:4|confirmed",
            "new_password_confirmation" => "required|min:4",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        if($exception->validator->errors()->has("password"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("password")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("new_password"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("new_password")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
        elseif($exception->validator->errors()->has("new_password_confirmation"))
        {
            $response = [
                "message" => $exception->validator->errors()->get("new_password_confirmation")[0],
            ];
            throw new HttpResponseException(response()->json($response, 422));
        }
    }
}
