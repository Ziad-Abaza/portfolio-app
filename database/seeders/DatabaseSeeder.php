<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ziad Abaza',
            'email' => 'zeyad.h.abaza@gmail.com',
            'password' => bcrypt('Zeyad@123'),
            'avatar' => public_path('images/avatare.png'),
            'email_verified_at' => now(),
        ]);

        $this->call([
            PortfolioContentSeeder::class,
        ]);
    }
}
