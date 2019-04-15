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
        \App\Models\Tariff::create([
            'params' => [
                1, 2, 3
            ],
        ]);
    }
}
