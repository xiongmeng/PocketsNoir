<?php

use Illuminate\Database\Seeder;
use App\Lottery;
class LotteryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Lottery::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            Lottery::create([
                'shop' => $faker->name,
                'first_prize' => 1,
                'second_prize' => 3,
                'third_prize' => 64,
                'forth_prize' => 232,
                'first_present_id' => 1,
                'second_present_id' => 1,
                'third_present_id' => 1,
                'forth_present_id' => 1,
                'second_prize_total' =>3,
                'status'=>1,
            ]);
        }

    }
}
