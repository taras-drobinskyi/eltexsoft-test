<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'view']);
        Permission::create(['name' => 'changeStatus']);

        // create roles and assign existing permissions
        $creator = Role::create(['name' => 'creator']);
        $creator->givePermissionTo('create');
        $creator->givePermissionTo('view');

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo('view');
        $moderator->givePermissionTo('changeStatus');

        $admin = Role::create(['name' => 'admin']);

        $user = \App\User::find(1);
        $user->assignRole($admin);
    }
}
