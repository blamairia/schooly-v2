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
        $adminEmail = env('ADMIN_EMAIL', 'admin@schooly.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin');
        $adminName = env('ADMIN_NAME', 'Administrator');

        // Update or create the admin user
        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($adminPassword),
            ]
        );

        $this->command->info("Admin user setup completed for: {$adminEmail}");
    }
}
