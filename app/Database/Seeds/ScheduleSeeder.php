<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;
use DateTime;

class ScheduleSeeder extends BaseSeeder
{
	public function run()
	{
		$max = 1000;

		$listActivities = [
			"Instalação",
			"Manutenção",
			"Limpeza",
			"Movimentação",
			"Desintalação",
			"Orçamento"
		];

		$listObjects = [
			"Ar Condicionado",
			"Aquecedor à Gás",
			"Caldeira",
			"Refrigerador",
			"Aquecedor Elétrico",
		];

		$listStatuses = [
			"daft", 
			"scheduled", 
			"rescheduled", 
			"canceled", 
			"closed"
		];

		$faker = $this->getFaker('pt_BR');

		for ($i = 0; $i < $max; $i++) {

			$uuid = $this->getNewUUidString();
			// get a randiom attendee
			$workerModel = new \App\Models\WorkerModel();
			$field = $workerModel->getRandomWorker("field");
			$field_uid = $field->worker_uid;

			$workerModel = new \App\Models\WorkerModel();
			$attendant = $workerModel->getRandomWorker("attendant");
			$attendant_uid = $attendant->worker_uid;

			$customerModel = new \App\Models\CustomerModel();
			$customer = $customerModel->getRandomCustomer();
			$customer_uid = $customer->customer_uid;

			// creata a new session 
			$scheduleModel = new \App\Models\ScheduleModel();
			$e = new \App\Entities\ScheduleEntity();

			$e->schedule_uid = $uuid;
			$e->worker_uid = $field_uid;
			$e->customer_uid = $customer_uid;
			$e->scheduled_by_uid = $attendant_uid;
			$e->schedule_service_name = $this->getRandomItem($listActivities);
			$e->schedule_object_name = $this->getRandomItem($listObjects);
			$e->schedule_status = $this->getRandomItem($listStatuses);
			$e->schedule_contact_name = $faker->firstName;
			$e->schedule_contact_phone = $faker->cellphoneNumber;
			$e->schedule_city = $faker->city;
			$e->schedule_address = $faker->address;

			$startDate   = new DateTime();
			$startDate->modify('-1 week');
			$endDate   = new DateTime();
			$endDate->modify('+1 week');
			$resultTime = $this->getRandomDate($startDate, $endDate);
			$resultTime->setTime(mt_rand(8, 18), mt_rand(0, 1) * 30);
			$e->schedule_date = $resultTime->format('Y-m-d H:i:s');

			if (in_array($e->schedule_status, ["closed"])) {
				$started_at = clone $resultTime;
				$r = mt_rand(0, 10);
				$started_at->modify("+$r minute");
				$e->schedule_started_at = $started_at->format('Y-m-d H:i:s');

				$ended_at = clone $resultTime;
				$r = mt_rand(25, 45);
				$ended_at->modify("+$r minute");
				$e->schedule_ended_at = $ended_at->format('Y-m-d H:i:s');
			}

			$scheduleModel->insert($e);
		}
	}
}
