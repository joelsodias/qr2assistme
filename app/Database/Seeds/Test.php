<?php

namespace App\Database\Seeds;


use \Faker\Factory;

class Test extends BaseSeeder
{
	public function run()
    {
		for($i = 0; $i < 50; $i++){
			$this->db->table("tests")->insert($this->generateTestProducts());
		}
    }

    public function generateTestProducts()
    {
        $faker = Factory::create();

		return [
			"name" => $faker->sentence(2),
			"description" => $faker->sentence(10),
			"cost" => $faker->numberBetween(100, 250),
			//"product_image" => $faker->imageUrl(100, 100)
			"product_image" => "https://source.unsplash.com/1600x900/?nature,water"
		];
    }
}
