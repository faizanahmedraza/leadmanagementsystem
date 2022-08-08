<?php

namespace App\Http\Resources\V1;

use App\Models\Lead;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class LeadCommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => unserialize($this->description) ?? '',
            'created_at' => $this->created_at ?? '',
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
