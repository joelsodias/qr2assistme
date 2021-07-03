<?php

namespace App\Models;

use App\Models\BaseModel;
use \Exception;
use Ramsey\Uuid\Nonstandard\UuidV6;

class ScheduleModel extends BaseModel
{
	protected $table      = 'schedule';
	protected $returnType     = 'App\Entities\ScheduleEntity';


	public function createSchedule($attendee_uid)
	{
		try {
			$schedule_uid = UuidV6::uuid6();
			$schedule_uid_str = (string) $schedule_uid;

			$e = new \App\Entities\ScheduleEntity();
			$e->schedule_uid = $schedule_uid_str;
			$e->attendee_uid = $attendee_uid;
			$e->schedule_status = "new";

			$this->save($e, true);
			$e = $this->find($this->getInsertID());
			return $e;
		} catch (Exception $error) {
			return null;
		}
	}

	public function getSchedules(string $worker_uid = null, $status = null, string $startdate = null, string $enddate = null)
	{



		try {
			$builder = $this->builder();

			$builder->select('  IF(schedule_date < NOW(), "delayed", schedule_status) AS schedule_status, schedule.*  ');

			if ($this->validateUuid($worker_uid, false)) {
				$builder->where("worker_uid", $worker_uid);
			}

			if (isset($startdate)) {
				$builder->where("schedule_date >= ", $startdate, true);
			}

			if (isset($enddate)) {
				$builder->where("schedule_date <= ", $startdate, true);
			}

			if (isset($status)) {
				if (is_array($status)) {
					$builder->whereIn("schedule_status", $status, true);
				} else $builder->where("schedule_status", $status, true);
			}

			$r = $builder->get()->getResult();

			if (isset($r) && count($r)) {
				return $r;
			} else {
				return null;
			}
		} catch (Exception $error) {
			return null;
		}


	}

	public function getSchedule(string $schedule_uid)
	{
		try {
			$builder = $this->builder();

			if ($this->validateUuid($schedule_uid, false)) {
				$builder->where("schedule_uid", $schedule_uid);
			} else return null;

			$r = $builder->get()->getResult();

			if (isset($r) && count($r)) {
				return $r[0];
			} else 	return null;
			
		} catch (Exception $error) {
			return null;
		}
	}

	public function getScheduleComplete(string $schedule_uid)
	{
		try {
			$builder = $this->builder();

			$builder->join("customer","schedule.customer_uid = customer.customer_uid","left outer");
			
			if ($this->validateUuid($schedule_uid, false)) {
				$builder->where("schedule_uid", $schedule_uid);
			} else return null;

			$r = $builder->get()->getResult();

			if (isset($r) && count($r)) {
				return $r[0];
			} else 	return null;
			
		} catch (Exception $error) {
			return null;
		}
	}


}
