<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Database\Factories\ProductFactory;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create a default user
        $user = \App\Models\User::factory()->create();

        // Create 10 fake products with the user ID of the default user
        Product::factory()
            ->count(10)
            ->create([
                'user_id' => $user->id,
            ]);
    }
}

