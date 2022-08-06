<?php

namespace App\Http\Requests\V1;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;
use Spatie\Permission\Models\Permission;

class LeadRequest extends RequestAbstract
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
            'name' => 'required|string|max:100',
            'description' => 'sometimes|nullable|string|max:1000',
            'email' => 'sometimes|nullable|email|max:100|email|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/i',
            'phone' => '',
            'address' => '',
            'status' => ($this->isMethod('put')) ? 'required|string|' . Rule::in(array_keys(Lead::STATUS)) : 'sometimes|nullable|string|' . Rule::in(array_keys(Lead::STATUS)),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'roles.*.in' => 'The selected roles are invalid.',
            'permissions.*.in' => 'The selected permissions are invalid.',
            'password.confirmed' => "Password did not matched.",
        ];
    }
}
