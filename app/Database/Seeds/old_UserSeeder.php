<?php

namespace App\Database\Seeds;


class UserSeeder extends BaseSeeder
{
	public function run()
	{

		$faker = $this->getFaker('pt_BR');

		$max = 100;
		for ($i = 0; $i < $max; $i++) {
			$uuid = $this->getUUid();

			$userModel = new \App\Models\UserModel();
			$e = new \App\Entities\UserEntity();
			$e->user_uid = $uuid;
			$firstName = $faker->firstName();
			$lastName = $faker->lastName();
			$lastName = str_replace(array("de ","das ","da ","D'"),array("","","",""),$lastName);
			$e->user_name =  $firstName . " " . $lastName;
			$e->user_email = $this->normalizeString($firstName) . "." . $this->normalizeString($lastName) . "@teste.com";
			$e->user_password_open = $this->getRandomPassword(15);
			$e->user_password = password_hash($e->user_password_open, PASSWORD_BCRYPT, ["cost" => 10]);

			$workerModel = new \App\Models\WorkerModel();
			$wtype = ["field","attendant"];
			if(mt_rand(0,1) == 1) {
				$worker = $workerModel->getRandomWorker($wtype[mt_rand(0,1)], false);
				$e->worker_uid = $worker->worker_uid;
			}

			$userModel->insert($e, true);
		}
	
		
	}
}
