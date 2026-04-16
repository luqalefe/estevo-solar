<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => (string) env('ADMIN_EMAIL', 'admin@estevo.tech')],
            [
                'name' => (string) env('ADMIN_NAME', 'Admin Estevo'),
                'password' => Hash::make((string) env('ADMIN_PASSWORD', 'admin123')),
            ],
        );
    }
}
