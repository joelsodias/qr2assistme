<?php

namespace App\Database\Seeds;

use Exception;
use Ramsey\Uuid\Codec\OrderedTimeCodec;




class QrObjectSeeder extends BaseSeeder
{



	public function run()
	{
		$faker = $this->getFaker('pt_BR');

		$items = [
			"Maquina de Lavar Roupa",
			"Ar Condicionado",
			"Aquecedor a Gas",
			"Maquina de Lavar Louça",
			"Microondas",
			"Geladeira",
		];

		$max = 100;
		for ($i = 0; $i < $max; $i++) {

			$uuid = $this->getNewUUidString();

			if (strlen($uuid) < 16) {
				throw new Exception("UUID shorter than 16");
			}


			$userModel = new \App\Models\CustomerModel();
			$customer = $userModel->getRandomCustomer();
			$customer_uid = $customer->customer_uid;

			$qrobjModel = new \App\Models\QrObjectModel();
			$e = new \App\Entities\QrObjectEntity();

			$e->object_uid = $uuid;
			$e->owner_uid = $customer_uid;
			$e->object_description = "Descrição do item {$uuid}";
			$e->object_name = $this->getRandomItem($items);
			$e->object_model = "OBJ-" . $this->getRandomString(15);
			$e->object_serial = $this->getRandomString(8, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");

			$qrobjModel->insert($e, true);
		}
	}
}
