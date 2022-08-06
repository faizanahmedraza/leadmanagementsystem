<?php

namespace App\Http\Resources;

use Exception;

class ErrorResponse extends BaseResponse
{
    public function __construct(Exception $error)
    {
        parent::__construct(null, $error, false, "Operation failed");
    }

    public function toArray($request)
    {
        return $this->wrapped();
    }
}
