<?php

namespace Database\Seeders\Admin;

use App\Enums\AdminRoles;
use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@1234'),
            'role' => AdminRoles::SUPER_ADMIN->value,
        ]);

        Admin::factory()->count(10)->create();

    }
}
