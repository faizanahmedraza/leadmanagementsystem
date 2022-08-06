<?php

namespace App\Models;

use App\Http\Traits\UserAuditTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use UserAuditTrait, CascadeSoftDeletes;

    const STATUS = ['pending' => 0, 'active' => 1, 'completed' => 2];

    protected $cascadeDeletes = ['leadUsers'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'phone', 'address', 'status', 'email'
    ];

    public function leadUsers()
    {
        return $this->belongsToMany(User::class,'user_leads', 'lead_id', 'user_id')->withPivot('created_by','updated_by')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(LeadComment::class,'lead_id','id');
    }
}
