<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class CustomerSeeder extends Seeder
{
	public function run()
	{
		$max = 100;
		for ($i = 0; $i < $max; $i++) {
			$CustomerModel = new \App\Models\CustomerModel();
            $customer = $CustomerModel->createCustomer();		
		}
	}
}
