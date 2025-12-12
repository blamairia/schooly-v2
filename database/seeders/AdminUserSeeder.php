<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD', 'password');
        $adminName = env('ADMIN_NAME', 'Administrator');

        // Don't create if a user with this email already exists
        if (User::where('email', $adminEmail)->exists()) {
            $this->command->info("Admin user already exists: {$adminEmail}");
            return;
        }

        User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($adminPassword),
        ]);

        $this->command->info("Admin user created: {$adminEmail}");
    }
}
