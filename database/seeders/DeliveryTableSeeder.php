<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeliveryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla deliveries.
        Delivery::truncate();

        $faker = \Faker\Factory::create();

        // Obtenemos la lista de todos los usuarios creados e
        // iteramos sobre cada uno y simulamos un inicio de
        // sesión con cada uno para crear entregas en su nombre

        $users = User::all();
        $image_name = $faker->image('public/storage/deliveries', 400, 300, null, false);
        foreach ($users as $user) {
            // iniciamos sesión con este usuario
            if ($user->role === 'ROLE_FARM') {
                JWTAuth::attempt(['email' => $user->email, 'password' => '123123']);
                // Y ahora con este usuario creamos algunas entregas
                $num_deliveries = 3;
                for ($j = 0; $j < $num_deliveries; $j++) {
                    Delivery::create([
                        'description' => $faker->sentence,
                        'quantity' => $faker->numberBetween(1, 30),
                        'image' => 'deliveries/' . $image_name,
                        'address' => $faker->address,
                        'for_user_id' => $faker->numberBetween(11, 20),
                    ]);
                }
            }
        }
    }
}
