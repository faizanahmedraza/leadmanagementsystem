<?php

namespace App\Http\Requests\V1;

use App\Models\Lead;
use Pearl\RequestValidate\RequestAbstract;

class AssignLeadRequest extends RequestAbstract
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
            'user_id' => 'required|numeric',
            'leads' => 'required|array',
            'leads.*' => 'required|in:'.implode(',',Lead::pluck('id')->toArray())
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
            'leads.*.in' => 'The selected leads are invalid.',
        ];
    }
}
