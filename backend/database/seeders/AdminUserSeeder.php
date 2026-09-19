<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => config('site.admin.email')],
            [
                'name' => config('site.admin.name'),
                'password' => config('site.admin.password'),
            ],
        );
    }
}
