<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);

        $admin = [
            'user_id' => 'AA000001',
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ];

        $user = User::create($admin);
        $user->assignRole('admin');
        $user->syncRoles('admin');

        $this->command->info("You have been created set of Permissions | Role and Define Super Admin \n email - admin@gmail.com \n Password - 123456");
    }
}
