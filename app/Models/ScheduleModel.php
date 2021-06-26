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



}