<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
            $response = response()->json([
                'status' => 'error',
                'message' => 'Ops! Some errors occurred',
                'errors' => $validator->errors()
            ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag);
    }
}
