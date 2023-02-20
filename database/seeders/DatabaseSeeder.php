<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
{
    $user = User::factory()->create();

    Product::factory()->count(10)->create([
        'added_by' => $user->id,
    ]);
}

}
