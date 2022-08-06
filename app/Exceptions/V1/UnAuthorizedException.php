<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class UnAuthorizedException extends BaseException
{
    public static function UserUnAuthorized(): self
    {
        return new self("User does not have valid access token!", '403');
    }

    public static function InvalidCredentials(): self
    {
        return new self("Invalid Credentials", '401');
    }

    public static function accountBlocked(): self
    {
        return new self("Your account has been blocked. Please contact with administrator.", '401');
    }

     public static function pendingAccount(): self
     {
         return new self("Your account is on pending. Please contact with administrator.", '401');
     }

    public static function unVerifiedAccount(): self
    {
        return new self("Your account is not verified. Please verify your account.", '401');
    }

    public static function review(): self
    {
        return new self("Your account is in review. Please contact with support for more details", '401');
    }
}
