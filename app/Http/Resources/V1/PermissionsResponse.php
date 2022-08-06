<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\BaseResponse;

class PermissionsResponse extends BaseResponse
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->wrapped(['collections' => (!$this->isEmpty()) ? PermissionResource::collection($this) : []]);
    }
}
