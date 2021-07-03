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

		$uuid = $this->getNewUUidString();

		if (!$data) {
			$e = new \App\Entities\CustomerEntity();
			$e->customer_uid = $uuid;
			$firstName = $faker->firstName();
			$lastName = $faker->lastName();
			$lastName = str_replace(array("de ", "das ", "da ", "D'"), array("", "", "", ""), $lastName);
			$name = $firstName . " " . $lastName;
			$email = $this->normalizeString($firstName) . "." . $this->normalizeString($lastName) . substr(str_shuffle("123456789"),1,2) . "@teste.com";
			$e->customer_email = $email;
			$e->customer_name = $name;
		} else {
			$e = new \App\Entities\CustomerEntity($data);
			$e->customer_uid = $e->customer_uid ?? $uuid;
		}

		$id = $this->insert($e, true);
		$e = $this->find($id);

		return $e;
	}
}
