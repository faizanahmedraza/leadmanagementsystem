<?php

namespace App\Http\Resources\V1;

use App\Models\Lead;
use Illuminate\Http\Resources\Json\JsonResource as Resource;
use App\Models\User;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => ($this->email) ? $this->email : $this->username,
            'last_logged_in' => $this->last_login,
            'image' => $this->image,
            'status' => array_search($this->status, User::STATUS),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'leads' => LeadResource::collection($this->whenLoaded('assignLeads'))
        ];

        if (isset($this->assign_leads_count)) {
            $user['leads_assign_count'] = $this->assign_leads_count ?? 0;
        }

        return $user;
    }
}
