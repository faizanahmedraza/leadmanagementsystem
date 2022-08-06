<?php

namespace App\Http\Services\V1;

use App\Exceptions\V1\FailureException;

class CloudinaryService
{
    public static function upload($image,$publicId = '')
    {
        $result = cloudinary()->upload($image,['public_id'=>uniqid('service-'.$publicId)]);

        if (!$result) {
            throw FailureException::serverError();
        }

        return (object)['url'=>$result->getPath(),'secureUrl'=>$result->getSecurePath(),'publicId'=>$result->getPublicId()];
    }
}
