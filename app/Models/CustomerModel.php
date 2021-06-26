<?php

namespace App\Models;

use App\Models\BaseModel;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\BaseBuilder;


class CustomerModel extends BaseModel
{
	protected $table                = 'customer';
	protected $returnType           = 'App\Entities\CustomerEntity';



	
	public function getRandomCustomer()
	{

		$builder = $this->builder();
		$builder->orderBy('RAND()');
		$builder->limit(1);
		$r = $builder->get()->getResult("array");

		if (count($r)) {
			$e = new \App\Entities\CustomerEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}

	public function getCustomer(string $customer_uid)
	{
		if ($this->validateUuid($customer_uid)) {
			$builder = $this->builder();
			//$builder->where("customer_uid", $this->getUuidBytes($customer_uid));
			$builder->where("customer_uid", $customer_uid);
			$builder->limit(1);
			$r = $builder->get()->getResult("array");
		}
		if (count($r)) {
			$e = new \App\Entities\CustomerEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}

	public function createCustomer($data = null)
	{
		$e = null;

		$faker = \Faker\Factory::create('pt_BR');

			$uuid = (string) Uuid::uuid6();

			if (!$data) {
				$e = new \App\Entities\CustomerEntity();
				$e->customer_uid = $uuid;
				$e->customer_email = $faker->email;
				$e->customer_name = $faker->name;
			} else {
				$e = new \App\Entities\CustomerEntity($data);
				$e->customer_uid = $uuid;
			}

			$id = $this->insert($e, true);
			$e = $this->find($id);

		return $e;
	}
}
