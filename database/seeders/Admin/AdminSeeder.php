<?php

namespace Database\Seeders\Admin;

use App\Enums\AdminRoles;
use App\Models\Admin\Admin;
use App\Models\Admin\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@1234'),
            'role' => AdminRoles::SUPER_ADMIN->value,
            'status' => 1,
        ]);
        $admin->permissions()->attach(Permission::all()->pluck('id')->toArray());
        Admin::factory()->count(10)->create();
    }
}
