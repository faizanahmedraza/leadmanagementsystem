<?php

namespace App\Models;

use App\Http\Traits\UserAuditTrait;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use UserAuditTrait;
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_id', 'verification_code','expiry',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
