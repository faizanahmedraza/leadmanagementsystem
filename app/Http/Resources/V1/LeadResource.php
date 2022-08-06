<?php

namespace App\Http\Resources\V1;

use App\Models\Lead;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class LeadResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $leads = [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'description' => unserialize($this->description) ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'address' => $this->address ?? '',
            'status' => array_search($this->status, Lead::STATUS),
            'created_at' => $this->created_at ?? '',
            'users' => UserResource::collection($this->whenLoaded('leadUsers')),
            'comments' => LeadCommentResource::collection($this->whenLoaded('comments'))
        ];

        if (isset($this->lead_users_count)) {
            $leads['assigned_users_count'] = $this->lead_users_count ?? 0;
        }

        return $leads;
    }
}
