<?php

use Illuminate\Database\Seeder;

class TariffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tariff = \App\Models\Tariff::create([
            'name' => 'week tariff',
            'duration' => 7,
            'price' => 500.45,
        ]);

        $tariff->locations()->attach([
            \App\Models\Location::create([
                'city' => 'Saint Petersburg',
            ])->id,
            \App\Models\Location::create([
                'city' => 'Moscow',
            ])->id
        ]);
    }
}
