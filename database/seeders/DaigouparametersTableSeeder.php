<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Daigouparameter;

class DaigouparametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Daigouparameter::firstOrCreate(
            ['name' => '韓幣'],
            [
            'unit' => '元',
            'name' => '韓幣',
            'price' => '300',
            ]
        );
        Daigouparameter::firstOrCreate(
            ['name' => '短袖（背心）'],
            [
            'unit' => '件',
            'name' => '短袖（背心）',
            'price' => '50',
            ]
        );
        Daigouparameter::firstOrCreate(
            ['name' => '短褲'],
            [
            'unit' => '件',
            'name' => '短褲',
            'price' => '60',
            ]
        );

        Daigouparameter::firstOrCreate(
            ['name' => '短裙'],
            [
            'unit' => '條',
            'name' => '短裙',
            'price' => '30',
            ]
        );


    }
}
