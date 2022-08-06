<?php

namespace App\Rules;

use App\Http\Models\Site;
use App\Models\AgencyDomain;
use Illuminate\Contracts\Validation\Rule;

class NotAllowedDomainRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (substr($value, -strlen(ltrim(env('AGENCY_BASE_DOMAIN'), '.'))) != ltrim(env('AGENCY_BASE_DOMAIN'), '.')) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You can't add this domain";
    }
}
