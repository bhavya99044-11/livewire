<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert(
            [
                'module' => 'admin',
                'name' => 'edit',
                'slug' => 'admin-edit',
            ],
            [
                'module' => 'admin',
                'name' => 'add',
                'slug' => 'admin-add',
            ],
            [
                'module' => 'admin',
                'name' => 'update',
                'slug' => 'admin-update',
            ],
            [
                'module' => 'admin',
                'name' => 'delete',
                'slug' => 'admin-delete',
            ],
            [
                'module' => 'admin',
                'name' => 'create',
                'slug' => 'admin-create',
            ]
        );
    }
}
