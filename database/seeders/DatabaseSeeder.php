<?php

namespace Database\Seeders;

use App\Models\CupCake;
use App\Models\CupcakeOrder;
use App\Models\Order;
use App\Models\User;
use Database\Factories\CupCakeOrderFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //Créer un user admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        //Créer 10 user
        User::factory(10)->create();

        //Créer 15 cupCake
        CupCake::factory(15)->create();

        $cupcakes = CupCake::all();

        for ($i = 1; $i < 11; $i++) {
            Order::factory(rand(1, 4))->create([
                'user_id' => $i,
            ])->each(function ($order) use ($cupcakes) {
                for ($j = 0; $j < rand(2, 10); $j++) {
                    $cupcake = $cupcakes->random();
                    $order->cupCakes()->attach($cupcake->id, ['quantity' => rand(1, 5), 'price' => $cupcake->price]);
                }
            });;
        }
    }
}
