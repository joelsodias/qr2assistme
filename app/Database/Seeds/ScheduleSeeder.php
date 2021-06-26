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

		$listStatuses = [
			"daft", "scheduled", "rescheduled", "canceled", "closed"
		];

		$faker = $this->getFaker('pt_BR');

		for ($i = 0; $i < $max; $i++) {

			$uuid = $this->getUUid();
			// get a randiom attendee
			$workerModel = new \App\Models\WorkerModel();
			$field = $workerModel->getRandomWorker("field");
			$field_uid = $field->worker_uid;
			
			$workerModel = new \App\Models\WorkerModel();
			$attendant = $workerModel->getRandomWorker("attendant");
			$attendant_uid = $attendant->worker_uid;

			// creata a new session 
			$scheduleModel = new \App\Models\ScheduleModel();
			$e = new \App\Entities\ScheduleEntity();

			$e->schedule_uid = $uuid;
			$e->worker_uid = $field_uid;
			$e->scheduled_by_uid = $attendant_uid;
			$e->service_name = $this->getRandomItem($listActivities);
			$e->schedule_status = $this->getRandomItem($listStatuses);
			$e->contact_name = $faker->firstName;
			$e->contact_phone = $faker->cellphoneNumber;
			$e->city = $faker->city;
			$e->address = $faker->address;

			$startDate   = new DateTime();
			$startDate->modify('-2 month');
			$endDate   = new DateTime();
			$endDate->modify('+2 month');
			$resultTime = $this->getRandomDate($startDate, $endDate);
			$resultTime->setTime(mt_rand(8, 18), mt_rand(0,1)*30);
			$e->schedule_date = $resultTime->format('Y-m-d H:i:s');  
			
			$started_at = clone $resultTime;
			$r = mt_rand(0, 10);
			$started_at->modify("+$r minute");
			$e->started_at = $started_at->format('Y-m-d H:i:s'); 

			$ended_at = clone $resultTime;
			$r = mt_rand(25, 45);
			$ended_at->modify("+$r minute");
			$e->ended_at = $ended_at->format('Y-m-d H:i:s'); 

			$scheduleModel->insert($e);
		}
	}
}
