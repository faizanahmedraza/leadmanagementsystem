<?php

namespace App\Http\Requests\V1;

use App\Rules\EmailFormatRule;
use Pearl\RequestValidate\RequestAbstract;

class LoginRequest extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData(): array
    {
        $all = parent::validationData();
        //Convert request value to lowercase
        if (isset($all['email'])) {
            $all['email'] = preg_replace('/\s+/', '', strtolower(trim($all['email'])));
        }
        return $all;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required','email:rfc,dns',new EmailFormatRule(),'max:100'],
            'password' => "required|string|max:100",
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
