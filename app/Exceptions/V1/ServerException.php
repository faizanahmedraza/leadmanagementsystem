<?php

namespace App\Exceptions\V1;

use App\Exceptions\BaseException;

class ServerException extends BaseException
{
    public static function errorMessage($message, $code): self
    {
        return new self(
            $message,
            $code
        );
    }
    

    public static function nothingToUpdate(): self
    {
        return new self(
            'Already Up to Date',
            '200'
        );
    }

    public static function emptyRequest(): self
    {
        return new self(
            'Empty Request',
            '200'
        );
    }
}
