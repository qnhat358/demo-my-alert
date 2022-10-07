<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
            'project_name' => ['required', 'min:5'],
            'account_id' => [
                'required',
                'integer',
                Rule::unique("projects", "account_id")->where(
                    function ($query) {
                        return $query->where(
                            [
                                ["project_name", "=", $this->project_name],
                                ["account_id", "=", $this->account_id]
                            ]
                        );
                    }
                )
            ]
        ];
    }

    public function messages()
    {
        return [
            'account_id.unique' => 'Duplicated data',
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
