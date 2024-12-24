<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Specialization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@docbook.com',
            'password' => '123456789',
            'phone'=>'1234567890',
            'role'=>'admin',

        ]);

        Service::Create(
            [
                'name' => 'Consult',
                'slug' => 'consult',
                'description' => 'Consult',
                'price' => '100',
                'category' => 'a',
                'is_available' => true

            ],
        );

        Specialization::Create(
            [
                'name' => 'Orthology',
                'slug' => 'orthology',
            ],
        );

    }
}
