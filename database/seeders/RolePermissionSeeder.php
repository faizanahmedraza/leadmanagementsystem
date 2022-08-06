<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::beginTransaction();

        $now = Carbon::now()->toDateTimeString();

        $roles = [
            ['name' => 'Admin', 'guard_name' => 'api', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager', 'guard_name' => 'api', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SalesPerson', 'guard_name' => 'api', 'created_at' => $now, 'updated_at' => $now],
        ];

        $adminLevelPermissions = [
            'access_all',
            'user_management_all',
            'user_management_read',
            'user_management_create',
            'user_management_update',
            'user_management_delete',
            'users_toggle_status',
            'roles_all',
            'roles_read',
            'roles_create',
            'roles_update',
            'roles_delete',
            'permissions_list'
        ];

        $managerPermissions = [
            'leads_all',
            'leads_read',
            'leads_create',
            'leads_update',
            'leads_delete',
            'leads_toggle_status',
            'leads_assign',
            'leads_comments',
        ];

        $permissionArray = collect(array_merge($adminLevelPermissions,$managerPermissions))->map(function ($permission) use($now) {
            return ['name' => $permission, 'guard_name' => 'api', 'created_at' => $now];
        });

        DB::table('permissions')->insert($permissionArray->toArray());
        DB::table('roles')->insert($roles);

        $role = Role::findByName('Admin');
        $role->givePermissionTo('access_all');

        $role = Role::findByName('Manager');
        $role->givePermissionTo($managerPermissions);

        $role = Role::findByName('SalesPerson');
        $role->givePermissionTo(['leads_all','leads_read','leads_comments']);

        DB::commit();
    }
}
