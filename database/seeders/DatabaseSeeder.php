<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Role
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);

        // Create Default Admin User
        $adminUser = \App\Models\User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@erp.bh',
            'password' => bcrypt('password'), // Default password
        ]);

        // Assign Role
        $adminUser->assignRole($adminRole);
    }
}
