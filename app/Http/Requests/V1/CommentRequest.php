<?php

namespace App\Http\Requests\V1;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;
use Spatie\Permission\Models\Permission;

class CommentRequest extends RequestAbstract
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lead_id' => 'required|numeric',
            'description' => 'required|string|max:1500',
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
