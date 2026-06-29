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


        User::create([
            'name' => 'test',
            'email' => 'test@rimba.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->call([WorkflowSeeder::class]);
        $this->call([JsonSeeder::class]);
        $this->call([ApiConfigSeeder::class]);
    }
}
