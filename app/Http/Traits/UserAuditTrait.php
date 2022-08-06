<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

trait UserAuditTrait
{
    use SoftDeletes { runSoftDelete as unusedSoftDelete; }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = (Auth::id()) ?: null;
        });
 
        // create a event to happen on updating
        static::updating(function ($model) {
            $model->updated_by = (Auth::id()) ?: null;
        });
 
        // create a event to happen on deleting
       /*  static::deleting(function ($model) {
            $model->updated_by = (Auth::id()) ?: null;
            $model->save();
        }); */
    }

    protected function runSoftDelete()
    {
        $query = $this->newQuery()->where($this->getKeyName(), $this->getKey());

        $this->{$this->getDeletedAtColumn()} = $time = $this->freshTimestamp();

        $query->update(array(
       $this->getDeletedAtColumn() => $this->fromDateTime($time),
             'updated_by' => (Auth::id()) ?: null
        ));
    }
}
