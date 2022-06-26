<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class dummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        for($i = 0 ; $i< 1000 ; $i++)
        DB::table('products')->insert([
            'name' => $faker->unique()->name,
            'price' => rand(0, 99999),
            'quantity' => rand(0, 200),
            'description' => $faker->text,
            'image' => '',
            'status' => 'pending'
        ]);
    }
}
