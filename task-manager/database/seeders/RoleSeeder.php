<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Employee', 'Manager', 'Editor'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
