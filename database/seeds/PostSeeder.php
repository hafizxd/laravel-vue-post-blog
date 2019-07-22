<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i = 0; $i < 30; $i++) {
          DB::table('posts')->insert([
            'title' => $faker->sentence(5),
            'content' => $faker->paragraph(4)
          ]);
        }
    }
}
