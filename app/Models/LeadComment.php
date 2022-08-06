<?php

namespace App\Models;

use App\Http\Traits\UserAuditTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadComment extends Model
{
    use SoftDeletes;

    protected $table = "lead_comments";

    protected $fillable = [
        'user_id',
        'lead_id',
        'description'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
