<?php

namespace App\Database\Seeds;


class WorkerSeeder extends BaseSeeder
{
	public function run()
	{

		$max = 100;
		$faker = $this->getFaker('pt_BR');
		$workerModel = new \App\Models\WorkerModel();
		$userModel = new \App\Models\UserModel();

		for ($i = 0; $i < $max; $i++) {

			$uuid = $this->getNewUUidString();
			$firstName = $faker->firstName();
			$lastName = $faker->lastName();
			$emailLastName = str_replace(array("de ", "das ", "da ", "D'"), array("", "", "", ""), $lastName);
			$name = $firstName . " " . $lastName;
			$email = $this->normalizeString($firstName) . "." . $this->normalizeString($emailLastName) . "@teste.com";

			$w = new \App\Entities\WorkerEntity();
			$w->worker_uid = $uuid;
			$types = ['field', 'attendant'];
			$type = $types[array_rand($types)];
			$w->worker_type = $type;
			$w->worker_name = $name;
			$w->worker_email = $email;
			$w->worker_avatar = '/images/avatar/worker.png';
			$w->worker_description = 'description for worker';

			$id = $workerModel->insert($w, true);
			$worker = $workerModel->find($id);

			$u = new \App\Entities\UserEntity();
			$u->user_email = $email;
			$u->user_password_open = $this->getRandomPassword(15);
			$u->user_password = password_hash($u->user_password_open, PASSWORD_BCRYPT, ["cost" => 10]);
			$u->worker_uid = $worker->worker_uid;

			$user = $userModel->createUser($u);

			if ($type == "attendant") {
				$chatuserModel = new \App\Models\ChatUserModel();
				$a = new \App\Entities\ChatUserEntity();
				$a->chat_user_uid = $uuid;
				$a->user_name = $name;
				$a->user_email = $email;
				$a->user_avatar = '/images/avatar/attendant.png';
				$a->user_type = 'attendant';
				$chatuser = $chatuserModel->createChatUser('attendant','N',$a);
			}
		}
	}
}
