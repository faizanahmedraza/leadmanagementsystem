<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class FailureException extends BaseException
{
    public static function serverError(): self
    {
        return new self(
            'Something went wrong and we have been notified about the problem',
            '500'
        );
    }
}
