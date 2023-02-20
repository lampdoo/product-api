<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;


class AddDefaultData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   

// ...

public function up()
{
    $user = new User([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => Hash::make('password'),
    ]);
    $user->save();

    $product = new Product([
        'name' => 'Product A',
        'price' => 9.99,
        'status' => 'active',
        'type' => 'item',
        'user_id' => $user->id,
    ]);
    $product->save();
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
