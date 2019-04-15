<?php

use Illuminate\Database\Seeder;

class UsersOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'name' => 'innistry',
            'phone' => '89523728001',
        ]);

        $tariff = \App\Models\Tariff::first();

        $i = 0;
        while (++$i < 8) {
            $started_at = now()->addDay(random_int(0, 20));

            \App\Models\Order::create([
                'user_id' => $user->id,
                'tariff_id' => $tariff->id,
                'location_id' => $tariff->locations()->first()->id,
                'address' => 'ул. Пушкин, д. Колотушкина',
                'started_at' => $started_at,
                'ended_at' => $started_at->addDay($tariff->duration),
            ]);
        }
    }
}
