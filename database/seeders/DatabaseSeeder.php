<?php

namespace Database\Seeders;

use App\Models\Product;
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
        // User::factory(10)->create();

        $user = User::create([
            'username' => 'jhon_doe',
            'password' => Hash::make('supersecret'),
        ]);

        Product::create([
            'title'          => 'Awesome T-Shirt',
            'price'          => 99.99,
            'description'    => 'High-quality cotton t-shirt',
            'category'       => 'Clothes',
            'images'         => ['https://placeimg.com/640/480/any'],
            'created_by'     => $user->username,
            'created_by_id'  => (string )$user->id,
            'updated_by'     => $user->username,
            'updated_by_id'  => (string) $user->id,
        ]);
    }
}