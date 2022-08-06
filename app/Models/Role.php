<?php

namespace App\Models;

use App\Http\Traits\UserAuditTrait;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use UserAuditTrait;

    const RESTRICT_ROLES = ['Admin', 'Manager', 'SalesPerson'];
}
