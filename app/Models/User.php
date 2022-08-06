<?php

namespace App\Models;

use App\Http\Traits\UserAuditTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens, HasRoles, HasPermissions, UserAuditTrait, CascadeSoftDeletes;

    const STATUS = ['pending' => 0, 'active' => 1, 'blocked' => 2, 'spammer' => 3, 'suspend' => 4, 'review' => 5];

    protected $cascadeDeletes = ['assignLeads'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','username', 'password', 'status','last_login','avatar'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function scopeAvoidRole($query, array $names)
    {
        return $query->whereHas('roles', function ($query) use ($names) {
            $query->whereNotIn('name', $names);
        });
    }

    public static function clean($value)
    {
        $value = trim(strtolower($value));

        if (strpos($value, ',') !== false) {
            return explode(",", $value);
        }

        return $value;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function assignLeads()
    {
        return $this->belongsToMany(Lead::class,'user_leads','user_id','lead_id')->withPivot('created_by','updated_by')->withTimestamps();
    }
}