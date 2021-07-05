<?php

namespace App\Database\Seeds;

use Exception;
use CodeIgniter\I18n\Time;
use DateTime;



class QrObjectSeeder extends BaseSeeder
{



	public function run()
	{
		$faker = $this->getFaker('pt_BR');

		$items = [
			"LAVADORA DE ROUPA", // 0
			"AR CONDICIONADO", // 1
			"AQUECEDOR DE AGUA A GAS", // 2
			"LAVADORA DE LOUÇA", // 3
			"MICROONDAS", // 4
			"REFRIGERADOR", // 5
		];

		$subitems[$items[1]][] = "GREE";
		$subitems[$items[1]][] = "BOSCH";
		$subitems[$items[1]][] = "MIDEA";
		$subitems[$items[1]][] = "LG";
		$subitems[$items[1]][] = "ELECTROLUX";
		$subitems[$items[1]][] = "SPRINGER";
		$subitems[$items[1]][] = "ELGIN";
		$subitems[$items[1]][] = "FUJITSU";
		$subitems[$items[1]][] = "CONSUL";
		$subitems[$items[1]][] = "KOMECO";
		$subitems[$items[1]][] = "CARRIER";
		$subitems[$items[1]][] = "HITACHI";
		$subitems[$items[1]][] = "DAIKIN";
		$subitems[$items[1]][] = "PHILCO";
		$subitems[$items[1]][] = "PANASONIC";
		$subitems[$items[1]][] = "RINETTO";
		$subitems[$items[1]][] = "ADMIRAL";
		$subitems[$items[1]][] = "TCL";
		$subitems[$items[1]][] = "AGRATTO";
		$subitems[$items[1]][] = "COMFEE";
		$subitems[$items[1]][] = "BRITANIA";
		$subitems[$items[1]][] = "FONTAINE";
		$subitems[$items[1]][] = "OLIMPIA SPLENDID";
		$subitems[$items[1]][] = "TRANE";
		$subitems[$items[1]][] = "MAXIFLEX";
		$subitems[$items[1]][] = "VOGGA";
		$subitems[$items[1]][] = "PHASER";
		$subitems[$items[1]][] = "YORK";

		$subitems[$items[2]][] = "INTEGRAL";
		$subitems[$items[2]][] = "ARISTON";
		$subitems[$items[2]][] = "BOSCH";
		$subitems[$items[2]][] = "CUMULOS";
		$subitems[$items[2]][] = "INOVA";
		$subitems[$items[2]][] = "KOMECO";
		$subitems[$items[2]][] = "LORENZETTI";
		$subitems[$items[2]][] = "ORBIS";
		$subitems[$items[2]][] = "RHEEM";
		$subitems[$items[2]][] = "KOBE";
		$subitems[$items[2]][] = "RINNAI";
		$subitems[$items[2]][] = "YUME";

		$subitems[$items[3]][] = "PANASONIC";
		$subitems[$items[3]][] = "PHILCO";
		$subitems[$items[3]][] = "BRITÂNIA";
		$subitems[$items[3]][] = "ELECTROLUX";
		$subitems[$items[3]][] = "CONTINENTAL";
		$subitems[$items[3]][] = "BRASTEMP";
		$subitems[$items[3]][] = "CONSUL";
		$subitems[$items[3]][] = "LG";



		$subitems[$items[0]] = $subitems[$items[3]];
		$subitems[$items[4]] = $subitems[$items[3]];
		$subitems[$items[5]] = $subitems[$items[3]];




		$max = 200;
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

			$startDate   = new DateTime();
			$startDate->modify('-2 year');
			$endInstallDate   = new DateTime();
			$endInstallDate->modify('-6 month');
			$resultInstall = $this->getRandomDate($startDate, $endInstallDate);
			$e->object_instalation_date = $resultInstall->format('Y-m-d H:i:s');

			$startDate   = new DateTime();
			$startDate->modify('-6 month');
			$endLastReviewDate   = new DateTime();
			//$endLastReviewDate->modify('+3 month');
			$resultLastReview = $this->getRandomDate($startDate, $endLastReviewDate);
			$e->object_last_review_date = $resultLastReview->format('Y-m-d H:i:s');
			
			$startDate   = new DateTime();
			$startDate->modify('+6 month');
			$endReviewDate   = new DateTime();
			$endReviewDate->modify('+1 year');
			$resultNextReview = $this->getRandomDate($startDate, $endReviewDate);
			$e->object_next_review_date = $resultNextReview->format('Y-m-d H:i:s');
			
			$startDate->modify('+1 year');
			$endMakerDate   = new DateTime();
			$endMakerDate->modify('+3 year');
			$resultMakerTime = $this->getRandomDate($startDate, $endMakerDate);
			$e->object_maker_warranty_exp_date = $resultMakerTime->format('Y-m-d H:i:s');

			$e->object_uid = $uuid;
			$e->object_owner_uid = $customer_uid;
			$e->object_description = "Descrição do item {$uuid}";
			$e->object_name = $this->getRandomItem($items);
			$e->object_brand = $this->getRandomItem($subitems[$e->object_name]);
			$e->object_model = $this->getRandomString(15, "A0B1C2D3E4F5G6H7I8J9KLMNOPQRSTUVWXYZ");
			$e->object_serial = $this->getRandomString(8, "A0B1C2D3E4F5G6H7I8J9KLMNOPQRSTUVWXYZ");
			$e->object_voltage = $this->getRandomItem(["110","220"]);


			$qrobjModel->insert($e, true);
		}
	}
}
