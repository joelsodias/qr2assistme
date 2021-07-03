<?php

namespace App\Database\Seeds;

class ChatUserSeeder extends BaseSeeder
{
	public function run()
	{

		$faker = $this->getFaker('pt_BR');
		$max = 100;


		// for ($i = 0; $i < $max; $i++) {
		// 	$uuid = $this->getNewUUidString();
        //     //$image = file_get_contents('http://source.unsplash.com/300x300/?face&'.$uuid);
		// 	//$file = file_put_contents($filename, $image);
		// 	$user = new \App\Models\ChatUserModel();
		// 	$e = new \App\Entities\ChatUserEntity();
		// 	$e->chat_user_uid = $uuid;
		// 	$e->user_name = $faker->name();
		// 	$e->user_email = $faker->email();
		// 	$e->user_avatar = '/images/avatar/attendant.png';
		// 	$e->user_type = 'attendant';
		// 	$user->insert($e, true);
		// }
		
		for ($i = 0; $i < $max; $i++) {
			$uuid = $this->getNewUUidString();

			$user = new \App\Models\ChatUserModel();
			$e = new \App\Entities\ChatUserEntity();
			$e->chat_user_uid = $uuid;
			$e->user_name = $faker->name();
			$e->user_email = $faker->email();
			$e->user_avatar = 'https://source.unsplash.com/300x300/?face&'.$uuid;
			$e->user_type = 'attendee';

			$user->insert($e, true);
		}
	}
}
