<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = env('SEED_ADMIN_PASSWORD');

        if (blank($password)) {
            $this->command->warn('SEED_ADMIN_PASSWORD is not set; skipping admin user seed.');

            return;
        }

        $email = env('SEED_ADMIN_EMAIL', 'support@assignmenthelpusa.com');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => env('SEED_ADMIN_NAME', 'Support'),
                'role'              => 'admin',
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin user seeded: {$email}");
    }
}
