<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class EmailFormatRule implements ImplicitRule
{
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
        if(!empty($value)){
            $array = explode("@",$value);

            if(count($array) === 2 && !empty($array[1])){
                $checkDotExist = explode(".",$array[1]);
                if (count($checkDotExist) >= 2 && !empty($checkDotExist[1])) {
                    return true;
                }
            }
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
        return ':attribute should be a valid email!';
    }
}
